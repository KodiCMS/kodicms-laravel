<?php namespace KodiCMS\Datasource;

use DB;
use Schema;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Contracts\SectionInterface;

class Section extends Model implements SectionInterface
{
	protected $table = 'datasource';
}