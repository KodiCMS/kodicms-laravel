<?php

namespace KodiCMS\Installer;

class EnvironmentTester
{
    const KEY_HAS_ERROR = 0;
    const KEY_REQUIRED_TEST = 1;
    const KEY_OPTIONAL_TEST = 2;

    /**
     * The registered custom tests.
     *
     * @var array
     */
    protected $customTests = [];

    /**
     * @var array
     */
    protected $tests = [];

    /**
     * @return array
     */
    public function check()
    {
        clearstatcache(true);

        $this->buildLocalTests();
        $this->buildExtendedTests();

        return $this->runTests();
    }

    /**
     * @param string $testName
     * @param array  $testData
     *
     * @return $this
     */
    public function extend($testName, array $testData)
    {
        $this->customTests[$testName] = $testData;

        return $this;
    }

    /**
     * @return array
     */
    protected function runTests()
    {
        $result = [
            static::KEY_HAS_ERROR     => false,
            static::KEY_REQUIRED_TEST => [],
            static::KEY_OPTIONAL_TEST => [],
        ];

        $isFailed = false;

        foreach ($this->tests as $key => $test) {
            if (strpos($key, 'optional') !== false) {
                $testType = static::KEY_OPTIONAL_TEST;
            } else {
                $testType = static::KEY_REQUIRED_TEST;
            }

            $passed = $this->runTest($test['condition']);

            $data = [
                'title'  => $test['title'],
                'passed' => $passed,
                'notice' => array_get($test, 'notice'),
            ];

            if ($passed) {
                $data['message'] = array_get($test, 'success_message', trans('installer::core.tests.pass'));
            } else {
                $data['message'] = array_get($test, 'error_message', trans('installer::core.tests.failed'));

                if ($testType === static::KEY_REQUIRED_TEST) {
                    $isFailed = true;
                }
            }

            $result[$testType][$key] = $data;
        }

        $result[static::KEY_HAS_ERROR] = $isFailed;

        return $result;
    }

    protected function buildExtendedTests()
    {
        foreach ($this->customTests as $key => $data) {
            $this->tests[$key] = $data;
        }
    }

    /**
     * @return array
     */
    protected function buildLocalTests()
    {
        $tests = get_class_methods($this);

        foreach ($tests as $method) {
            if (strpos($method, 'test') === false) {
                continue;
            }

            $test = $this->$method();

            $this->tests[substr($method, 4)] = $test;
        }
    }

    /**
     * @param $test
     *
     * @return bool
     */
    protected function runTest($test)
    {
        if (is_callable($test)) {
            return $test();
        }

        return $test;
    }

    /***********************************************************************************
     * Tests
     ***********************************************************************************/

    /**
     * @return array
     */
    public function testPHP()
    {
        return [
            'title'           => 'PHP Version',
            'condition'       => function () {
                return version_compare(PHP_VERSION, '5.5.9', '>=');
            },
            'error_message'   => trans('installer::core.tests.errors.php_version', ['version' => PHP_VERSION]),
            'success_message' => PHP_VERSION,
        ];
    }

    /**
     * @return array
     */
    public function testCacheStorage()
    {
        $storagePath = base_path('storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'cache');

        return [
            'title'           => 'Cache storage',
            'condition'       => function () use ($storagePath) {
                return is_writable($storagePath);
            },
            'error_message'   => trans('installer::core.tests.errors.cache_storage', [':path' => $storagePath]),
            'success_message' => $storagePath,
        ];
    }

    /**
     * @return array
     */
    public function testSessionsStorage()
    {
        $storagePath = base_path('storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'sessions');

        return [
            'title'           => 'Sessions storage',
            'condition'       => function () use ($storagePath) {
                return is_writable($storagePath);
            },
            'error_message'   => trans('installer::core.tests.errors.sessions_storage', [':path' => $storagePath]),
            'success_message' => $storagePath,
        ];
    }

    /**
     * @return array
     */
    public function testViewsStorage()
    {
        $storagePath = base_path('storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'views');

        return [
            'title'           => 'Views storage',
            'condition'       => function () use ($storagePath) {
                return is_writable($storagePath);
            },
            'error_message'   => trans('installer::core.tests.errors.views_storage', [':path' => $storagePath]),
            'success_message' => $storagePath,
        ];
    }

    /**
     * @return array
     */
    public function testLogsStorage()
    {
        $storagePath = base_path('storage'.DIRECTORY_SEPARATOR.'logs');

        return [
            'title'           => 'Logs storage',
            'condition'       => function () use ($storagePath) {
                return is_writable($storagePath);
            },
            'error_message'   => trans('installer::core.tests.errors.logs_storage', [':path' => $storagePath]),
            'success_message' => $storagePath,
        ];
    }

    /**
     * @return array
     */
    public function testBootstrapCache()
    {
        $storagePath = base_path('bootstrap'.DIRECTORY_SEPARATOR.'cache');

        return [
            'title'           => 'Bootstrap cache',
            'condition'       => function () use ($storagePath) {
                return is_writable($storagePath);
            },
            'error_message'   => trans('installer::core.tests.errors.bootstrap_cache', [':path' => $storagePath]),
            'success_message' => $storagePath,
        ];
    }

    /**
     * @return array
     */
    public function testOpenSSL()
    {
        return [
            'title'           => 'OpenSSL Extension',
            'condition'       => function () {
                return extension_loaded('openssl');
            },
            'error_message'   => trans('installer::core.tests.errors.openssl'),
            'success_message' => trans('installer::core.tests.messages.pass'),
        ];
    }

    /**
     * @return array
     */
    public function testPDO()
    {
        return [
            'title'           => 'PDO Extension',
            'condition'       => function () {
                return extension_loaded('pdo');
            },
            'error_message'   => trans('installer::core.tests.errors.pdo'),
            'success_message' => trans('installer::core.tests.messages.pass'),
        ];
    }

    /**
     * @return array
     */
    public function testMbstring()
    {
        return [
            'title'           => 'Mbstring Extension',
            'condition'       => function () {
                return extension_loaded('mbstring');
            },
            'error_message'   => trans('installer::core.tests.errors.mbstring'),
            'success_message' => trans('installer::core.tests.messages.pass'),
        ];
    }
}
