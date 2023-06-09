<?php

function save_mess(){ // Функция для сохранения сообщений в базу данных
    
    global $db; // Находим базу данных в глобальном пространстве имён
    $name = mysqli_real_escape_string($db, $_POST['name']);// Заполняем переменную имени
    $text = mysqli_real_escape_string($db, $_POST['txt']);// Заполняем переменную сообщения
    $query = "INSERT INTO guestbook (name, text) VALUES ('$name','$text')";// Формируем запрос заполнения guestbook поля имени и сообщения
	//Остальные поля заполняются автоматически
    mysqli_query($db, $query); // Выполняем запрос
} 

function get_mess(){
    global $db; // Находим базу данных в глобальном пространстве имён
    $res = mysqli_query($db, "SELECT * FROM guestbook ORDER BY id ASC");// Формируем запрос в котором принимаем записи
    return mysqli_fetch_all($res, MYSQLI_ASSOC);// Возвращаем массив с записями
}

// Здесь содержится функционал гостевой книги
$file = 'book.txt'; //  Файл, куда мы сохраняем записи
$date = date('d.m.Y H:i');// Время отправки сообщения
if(isset($_POST['name'])):// Проверка, что элемент с таким ключом существует
	$name = $_POST['name'];// Присваиваем переменной соответствующее значение из записи
endif;
if(isset($_POST['secret'])):// Проверка, что элемент с таким ключом существует
	$secret = $_POST['secret'];// Присваиваем переменной соответствующее значение из записи
endif;

if(isset($_POST['txt'])):// Проверка, что элемент с таким ключом существует
	$text = strip_tags(trim($_POST['txt']));// Присваиваем переменной соответствующее значение из записи
endif;
if(isset($text)):// Проверка, что переменная не пуста
	$text =  str_replace("\n", "", $text);// Удаляем переносы
endif;

$mess = '';// Присваиваем переменной mess начальное значение пустой строки




if (isset($_GET['page'])) {// Проверка, что переменная не пуста
	$page = $_GET['page'];// Присваиваем переменной соответствующее значение номера страницы
}else{
	$page = 1;// Присваиваем переменной значение 1 если соответствующее значение номера страницы отсутствует
}

$onepages = 10;// Задаём количество записей на одной странице
$from = ((int)$page -1) * $onepages;

$arr = @get_mess(); // Заполняем массив записями из БД
if (!is_array($arr)) $arr = [];// Делаем массив пустым если записи отсутствуют
$cou = count($arr);// Находим количество записей

$first=(int)$page*$onepages-$onepages;// Находим номер первой записи, отображающейся на данной странице
$last=min($first + $onepages - 1, ((int)$first+($cou - ($page-1)*$onepages)-1));// Находим номер последней записи, отображающейся на данной странице

$prev = (int)$page -1;// Находим номер предыдущей странциы
$post = (int)$page +1;// Находим номер следующей странциы

$pagesCount = ceil($cou / $onepages);// Находим общее количество страниц

if (isset($_POST['add'])) {// Проверка, что элемент с таким ключом существует
if (!empty($name) && !empty($text)) {// Проверка, что имя и текст записи не пусты

	save_mess(); // Добавляем записи в базу данных

	$random = time(); // Создаём переменную со случайным значением
	if (!empty($page)) 
	{
		Header("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['SCRIPT_NAME']}?page=$pagesCount&$random#form");// Преходим на страницу с нашим добавленным сообщением
		exit;
	}
	
	}
}







