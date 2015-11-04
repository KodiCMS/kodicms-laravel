<?php

namespace KodiCMS\Filemanager\elFinder;

use Illuminate\Http\Request;

class Connector
{
    const FILE_SYSTEM = VolumeLocalFileSystem::class;
    const FTP = VolumeFTP::class;

    /**
     * elFinder instance.
     *
     * @var elFinder
     **/
    protected $elFinder;

    /**
     * Options.
     *
     * @var aray
     **/
    protected $options = [];

    /**
     * undocumented class variable.
     *
     * @var string
     **/
    protected $header = 'Content-Type: application/json';

    /**
     * @var Requests
     */
    protected $request;

    /**
     * @param elFinder $elFinder
     * @param bool     $debug
     */
    public function __construct(elFinder $elFinder, Request $request, $debug = false)
    {
        $this->elFinder = $elFinder;
        $this->request = $request;

        if ($debug) {
            $this->header = 'Content-Type: text/html; charset=utf-8';
        }
    }

    public function run()
    {
        $isPost = $this->request->getMethod() === 'POST';

        $src = $this->request->all();

        $cmd = isset($src['cmd']) ? $src['cmd'] : '';
        $args = [];

        if (! $this->elFinder->loaded()) {
            $this->output([
                'error' => $this->elFinder->error(elFinder::ERROR_CONF, elFinder::ERROR_CONF_NO_VOL),
                'debug' => $this->elFinder->mountErrors,
            ]);
        }

        // telepat_mode: on
        if (! $cmd && $isPost) {
            $this->output([
                'error'  => $this->elFinder->error(elFinder::ERROR_UPLOAD, elFinder::ERROR_UPLOAD_TOTAL_SIZE),
                'header' => 'Content-Type: text/html',
            ]);
        }
        // telepat_mode: off

        if (! $this->elFinder->commandExists($cmd)) {
            $this->output(['error' => $this->elFinder->error(elFinder::ERROR_UNKNOWN_CMD)]);
        }

        // collect required arguments to exec command
        foreach ($this->elFinder->commandArgsList($cmd) as $name => $req) {
            $arg = $name == 'FILES' ? $_FILES : (isset($src[$name]) ? $src[$name] : '');

            if (! is_array($arg)) {
                $arg = trim($arg);
            }
            if ($req && (! isset($arg) || $arg === '')) {
                $this->output(['error' => $this->elFinder->error(elFinder::ERROR_INV_PARAMS, $cmd)]);
            }
            $args[$name] = $arg;
        }

        $args['debug'] = isset($src['debug']) ? ! ! $src['debug'] : false;

        return $this->output($this->elFinder->exec($cmd, $this->input_filter($args)));
    }

    /**
     * Output json.
     *
     * @param  array  data to output
     *
     * @return void
     * @author Dmitry (dio) Levashov
     **/
    protected function output(array $data)
    {
        $header = isset($data['header']) ? $data['header'] : $this->header;

        unset($data['header']);

        if ($header) {
            if (is_array($header)) {
                foreach ($header as $h) {
                    header($h);
                }
            } else {
                header($header);
            }
        }

        if (isset($data['pointer'])) {
            rewind($data['pointer']);
            fpassthru($data['pointer']);

            if (! empty($data['volume'])) {
                $data['volume']->close($data['pointer'], $data['info']['hash']);
            }

            return;
        } else {
            if (! empty($data['raw']) && ! empty($data['error'])) {
                return $data['error'];
            } else {
                return $data;
            }
        }
    }

    /**
     * Remove null & stripslashes applies on "magic_quotes_gpc".
     *
     * @param  mixed $args
     *
     * @return mixed
     * @author Naoki Sawada
     */
    private function input_filter($args)
    {
        if (is_array($args)) {
            return array_map([&$this, 'input_filter'], $args);
        }

        $res = str_replace("\0", '', $args);

        return $res;
    }
}
