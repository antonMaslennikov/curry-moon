<?php
namespace admin\application\controllers;

use application\models\feedback;
use smashEngine\core\App as App;
use smashEngine\core\exception\appException;

use \application\models\mailTemplate;

class Controller_ extends \smashEngine\core\Controller
{
	protected $layout = null;

	protected $excluded_controllers = [
		'admin\\application\\controllers\\Controller_access'=>true,
		'admin\\application\\controllers\\Controller_503'=>true,
	];

	protected $breadcrumbs = [
		'/' => '<i class="fa fa-dashboard"></i> Админка',
	];

	public function __construct(\Routing\Router $router)
	{
		parent::__construct($router);

		// Текущий пользователь
		$this->user = \admin\application\models\WebUser::load();

		if (!isset($this->excluded_controllers[get_called_class()])) {

			if (!$this->user->authorized) {

				$this->page->go('/admin/login');
			} elseif (!isset($this->user->role)) {

				$this->page403();
			}
		}

		// кэшируем переменные
		//if (!$this->VARS = App::memcache()->get('VARS')) {
			$sth = App::db()->query("SELECT `variable_name`, `variable_value` FROM `variables`");
			foreach ($sth->fetchAll() AS $v) {
				$this->VARS[$v['variable_name']] = $v['variable_value'];
			}
			//App::memcache()->set('VARS', $this->VARS, false, 7200);
		//}

		// получаем курс доллара
		$this->VARS['usdRate'] = usdRateDaily();

		// импортируем статику на страницу
		$this->page->import(array());

        // переопределяем в админке путь до папки с шаблонами писем в пользовательскую часть
        mailTemplate::$tpl_folder = '../' . mailTemplate::$tpl_folder;
        
		// =====================================================================================================================
		// Отправляем все глобальные объекты в шаблон
		// =====================================================================================================================
		$this->view->setVar('CONTROLER', $this);
		$this->view->setVar('USER', $this->user);
		$this->view->setVar('PAGE', $this->page);
		$this->view->setVar('L', $this->page->translate);
		$this->view->setVar('basket', $this->basket);

		$this->view->setVar('CURRENT_URL', urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
		$this->view->setVar('REQUEST_URI', $_SERVER['REQUEST_URI']);
		$this->view->setVar('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
		$this->view->setVar('PAGE_URI', toTranslit(urldecode($_SERVER['REQUEST_URI'])));
		$this->view->setVar('reqUrl', $this->page->reqUrl);
		$this->view->setVar('ogUrl', $this->page->ogUrl);
		$this->view->setVar('ogUrlAlt', rtrim((strpos($this->page->url, '/' . $this->page->lang) === 0 ? substr($this->page->url, 3) : $this->page->url), '/'));
		$this->view->setVar('mainUrl', mainUrl);

		$this->view->setVar('dollarRate', $this->VARS['usdRate']);
		$this->view->setVar('datetime', array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'), 'hour' => date('H'), 'minute' => date('i'), 'dayofweek' => date('w')));

		$this->view->setVar('contact_phone', $this->VARS['contactPhone1']);
		$this->view->setVar('operation_time', $this->VARS['operation_time']);
		$this->view->setVar('operation_time_1', $this->VARS['operation_time_1']);
		$this->view->setVar('operation_time_2', $this->VARS['operation_time_2']);
		$this->view->setVar('operation_time_3', $this->VARS['operation_time_3']);

		$this->view->setVar('session_name', session_name());
		$this->view->setVar('session_id', session_id());

		$this->view->setVar('rand', rand(1, 2));
		$this->view->setVar('random', rand(1, 1000));

		$this->view->setVar('csrf_token', $_SESSION['csrf_token']);
		$this->view->setVar('appMode', appMode);
	}


	function page403()
	{
		header('HTTP/1.1 403 Forbidden');
		$this->view->generate('403.tpl');
		exit();
	}



	public function setTemplate($page) {

		if ($this->layout !== null) {

			$this->page->index_tpl = $this->layout;
		}

		$this->page->tpl = $page;
	}

	public function setTitle($title) {

		$this->page->title = $title;
	}

	/**
	 * @param array $data Массив в виде url=> Расшифровка
	 */
	protected function setBreadCrumbs($data) {

		$this->breadcrumbs = array_merge($this->breadcrumbs, $data);
	}


	protected function generateBreadcrumbs() {

		$breadcrumbs = '';
		if (count($this->breadcrumbs)) {

			//$this->breadcrumbs = array_merge($this->breadcrumbs, [$this->page->title]);

			foreach ($this->breadcrumbs as $url => $label) {

				if (is_string($url)) {

					$breadcrumbs  .= sprintf('<li><a href="%s">%s</a></li>', $url, $label);
				} else {

					$breadcrumbs .= sprintf('<li class="active">%s</li>', $label);
				}
			}
		}

		$this->view->setVar('breadcrumbs', $breadcrumbs);
	}

	protected function isAssocKey() {

		return ;
	}

	public function render() {

		$this->generateBreadcrumbs();

		$this->view->setVar('count_new_fb', (new feedback())->countNew());

		$this->view->generate($this->page->index_tpl);
	}
}