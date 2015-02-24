<?php
	use core\sql\Model_ORM;
	use domain\extproperty\Model_ExtpropertyObject;
	use app\utils\EventDispatcher;
	//use core\drive\Model_Image;

	class Controller_Index extends Controller_Base
	{
		public function __construct(){}

		public function index($request)
		{	
			$db = \app\utils\Debugger::getInstance();

			$doc = \app\view\Document::getInstance();
			$doc->load('main');
			$doc->addStyle('/css/style.css');

			$block = new \app\view\Node();
			$block->load('button');

			$doc->assign('#article',$block);

			\app\view\Responser::sendHtml($doc->render());

			$db->saveState('end');
			$db->dump();
		}	

		public function upload($r)
		{
			var_dump(\app\Request::isFileOverflow());

			/*
			echo '<pre>';

			var_dump(array(
				"post"  => $_POST,
				"get"   => $_GET,
				"request" => $_REQUEST,
				"server"=> $_SERVER,
				"files" => $_FILES
			));*/
		}

		public function js($r)
		{
			echo '<script type="text/javascript" src="/js/jquery/jquery-1.7.2.min.js"></script>';
			echo '<script src="/js/jquery/jquery-ui-1.9.2.custom.min.js"></script>';
			echo '<script src="/js/medusa/medusa.core.js"></script>';
			echo '<script src="/js/medusa/medusa.popupbox.js"></script>';
			echo '<script src="/js/medusa/medusa.dialog.js"></script>';
			echo '<script src="/js/medusa/medusa.upload.js"></script>';
			echo '<script src="/js/medusa/medusa.tooltip.js"></script>';
			echo '<script src="/js/medusa/medusa.tabs.js"></script>';
			echo '<script src="/js/medusa/medusa.collapse.js"></script>';
			echo '<script src="/js/medusa/medusa.carousel.js"></script>';
			
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/normalize.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.base.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.dialog.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.popupbox.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.tooltip.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.tabs.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.collapse.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.upload.css" />';
			echo '<link rel="stylesheet" type="text/css" href="/css/medusa/medusa.carousel.css" />';

			echo "
			<div style='position:relative;height:100%;overflow:auto'>
			<br>&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;
			<textarea tooltip='wassup motherfucker'></textarea>
			<br>
			
			<br>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<div style='display:inline-block'>sasai lalak</div>
			<imgg style='margin:20px;border:1px solid #345' tooltip='на ачко пасажить жи есть на ачко пасажить жи есть на ачко пасажить жи есть на ачко пасажить жи есть на ачко пасажить жи есть' src='https://cdn1.iconfinder.com/data/icons/ie_Financial_set/32/13.png' />
			
			<div style='width:200px'>
				
					
					<form method='post' action='/upload' enctype='multipart/form-data'>
						<input type='text' /><input type='button' value='okay'/>
						<div class='medusa-upload'>
							<div class='medusa-upload-title'>Загрузить новый файл</div>	
								<input type='file' class='medusa-upload-file' name='upl[]' multiple/>
						</div>
					</form>
				
			</div>
			<div>
				<input type='text' /><input type='button' value='okay'/>
			</div>
			<br>
			<br>
			<div style='border:1px solid #ddd;padding:10px;margin:12px'>
				<div id='tab'>
					<ul>
						<li href='#oner' url='/link/to/load'>One Tab</li>
						<li href='#twor'>Two Tab</li>
					</ul>
					<div id='oner'>One content</div>
					<div id='twor'>Two content</div>
				</div>
			</div>
			<div>
				<div></div>
				56
			</div>
			<div style='width:500px;border:1px solid #ddd;padding:10px;margin:12px'>
				<dl id='tabso' style=''>
					<dt href='#one'>First Tab header</dt>
					<dd id='one'>Музыка R&B и Funk ранних 80-ых не похожа ни на то, какой эта музыка была в 70-е годы с её классиками - Джеймсом Брауном, Джорджем Клинтоном, Стиви Уандером, с эпохой беззаботного дискофанка и прическами в стиле «Афро»</dd>
					<dt href='#two'>Second Tab Header Long Item</dt>
					<dd id='two'>Эта музыка является причудливым синтетическим коктейлем, в котором перемешан гламурный блеск, шум городских трущоб, гулкий рокот кастомизированных автомобилей и запах бензина, романтика уличного брейкданса, яркий ночной свет небоскребов и неоновых вывесок, пропитанный невероятной сексуальностью и сумасшедшей энергетикой.</dd>
					<dt href='#three'>Three</dt>
					<dd id='three'>Применяется для изменения алгоритма расчета ширины и высоты элемента.</dd>
					<dt href='#four'>Four</dt>
					<dd id='four'>
						String concatenation is often easier on your eyes, but slower and more memory intensive. If speed and performance is a goal than you should always delimit your JavaScript strings with backslashes and not plus signs.алка - искаженное лолка. лолка - тот, над кем смеются, кого троллят.сасай такое же искажение - соси.смысл в том, что этот язык появился для высмеивания неграмотных, тупых и толстых троллей-школьников, в кач-ве пародии. сейчас же используется людьми, в большинстве своем, не понимающих сути явления.объяснил?)алка - искаженное лолка. лолка - тот, над кем смеются, кого троллят.сасай такое же искажение - соси.смысл в том, что этот язык появился для высмеивания неграмотных, тупых и толстых троллей-школьников, в кач-ве пародии. сейчас же используется людьми, в большинстве своем, не понимающих сути явления.String concatenation is often easier on your eyes, but slower and more memory intensive. If speed and performance is a goal than you should always delimit your JavaScript strings with backslashes and not plus signs.алка - искаженное лолка. лолка - тот
						Основывается на стандартах CSS, при этом свойства width и height задают ширину и высоту контента и не включают в себя значения отступов, полей и границ.
						<div id='subtabs'>
							<div id='xxx'>X-x-x</div>
							<div id='yyy'>Y-y-y</div>
						</div>
					</dd>
				</dl>

			</div>
			<br>
			<div id='carousel'>
				<div><img src='http://learn.php/a/banners/one.jpg' /></div>
				<div title='ebeleh' url='http://google.com/'><img src='http://learn.php/a/banners/two.jpg' /></div>
				<div title='ovarah' url='http://facebook.com/'><img src='http://learn.php/a/banners/three.jpg' /></div>
				<div><img src='http://learn.php/a/banners/four.jpg' /></div>
			</div>
			</div>";
		}

		public function ok($r)
		{
			$doc = \app\view\Document::getInstance();
			$doc->load('main');
			$doc->setTitle('HELLO');

			$node = new \app\view\Node();

			$node->load('loop',array(
				"title" => "SASAI LALKA",
				"table" => array(
					array(1,4,6,5,7,6,8),
					array(6,5,7,6,8,3,2),
					array(9,3,2,1,3,2,3),
					array(1,1,5,4,6,5,4),
					array(4,6,7,6,5,3,2)
				)
			));

			$b = new \app\view\Node();
			$b->load('button');

			$doc->assignArray(
				array(
					'#lalka' => $node,
					'#button' => $b
				)
			);

			echo $doc->render();
		}

		public function sql()
		{	
			/*
			Model_ORM::take('employee')->insert(

					array(
						'name' => NULL
					)
	
			)
			->exec();
			*/
			
			$t = Model_ORM::take('employee')
			->flags(array(
				"SQL_CALC_FOUND_ROWS"
			))
			->select(array(
				"id",
				"name"
			))
			->where()
			->exec()
			->fetchAll();
			
			echo '<pre>';
			var_dump($t);
			echo '</pre>';
			
			$m = Model_ORM::take()
			->exec("SHOW TABLES")
			->fetchAll();

			echo '<pre>';
			var_dump($m);
			echo '</pre>';
		}

		public function img()
		{
			\core\drive\Model_Image::setWatermark(
				DOCUMENT_ROOT . "/kitten.jpg",
				DOCUMENT_ROOT . "/water2.png",
				DOCUMENT_ROOT . "/w.jpg",
				array(
					"x" => "right",
					"y" => "bottom",
					"padding_x" => 120,
					"padding_y" => 120,
					"angle" => 0,
					"mode" => "position",
					"opacity" => 0
					//"zoom" => 15
				)
			);
		}

		public function image()
		{
			session_start();
			\core\drive\Model_Image::$captcha_length = 4;
			\core\drive\Model_Image::getCaptcha($_SESSION['lalka']);
			return;
			
			$first  = DOCUMENT_ROOT ."/x.gif";
			$second = DOCUMENT_ROOT . '/x2.gif';

			foreach(array('jpg','png','gif') as $file)
				foreach(array('resizeArea','resizeProp','resizeForce') as $method){
					
					\core\drive\Model_Image::$method(
						DOCUMENT_ROOT ."/x.".$file,
						null,
						array(
							"width" => 100,
							"height" => 100,
							"left"=> 400,
							"top"=>300,
							"selectionWidth"  => 100,
							"selectionHeight" => 100
						)
					);

					echo '<img src="' ."/x" . $method . ".".$file.'">';
				}
			
			echo '<style>body{background-color:#336699;}</style>';
			
			//\core\drive\Model_Image::gif2png(DOCUMENT_ROOT . '/lalka.gif',DOCUMENT_ROOT . '/lalka.png');
		}

		public function ext()
		{
			$e = new \domain\extproperty\Model_Extproperty();

			$e->addProperty(array(
				'name' => 'Lalka',
				'data_type' => 'select',
				'object_id' => 5,
				'object_set_id' => 6,
				'group_id' => 675,
				'variants' => array(
					array(
						'value' => 'One'
					),
					array(
						'value' => 'Two'
					)
				)
			));
		}

		public function json($r)
		{
			var_dump(json_decode(file_get_contents('php://input'),true));
		}

		public function tester($r)
		{
			$source = array(1,2,3,4,5,6);
			$values = array(6,2,5,0,9,7);

			echo '<pre>';
			var_dump(\core\utils\Model_Array::unsetValues($source,$values));
		}
	}
?>