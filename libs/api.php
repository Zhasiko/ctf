<?
$api = new API;
class API
{
	public $Pages;
	public $Pag;
	public $Strings;
	public $Managers;
	
	# Подгрузка
	function __construct() 
	{
		$this->Pages 	= new Pages;
		$this->Pag 		= new Pagination;
		$this->Strings 	= new Strings;
		$this->Managers	= new Managers;
	}
	
	# Выгрузка
	function __destruct()
	{
		unset($this->Pages);
		unset($this->Pag);
		unset($this->Strings);
		unset($this->Managers);
	}	
}

# Класс страниц
class Pages
{
		private $lang;
		private $url;
		private $query;
		private $elements_count;
		private $per_page;
		private $page;
		
		public $pages;
		public $start_from;
	
		// Установка значений
		public function setvars($lang, $url, $query, $elements_count, $per_page, $page)
		{
			$this->lang				= $lang;
			$this->url 				= $url;
			$this->query			= eregi_replace("&p=[[:digit:]]{1,100}", "", $query);
			$this->query			= eregi_replace("p=[[:digit:]]{1,100}", "", $this->query);
			$this->elements_count 	= intval($elements_count);
			$this->per_page 		= intval($per_page);
			$this->page 			= intval($page);
		
			$this->count_pages();
			$this->set_page();
			$this->set_start();
		}	
	
		// Подсчет страниц
		private function count_pages()
		{
			$this->pages = ceil($this->elements_count / $this->per_page);
		}
	
		// Начинасть с
		private function set_start()
		{
			$this->start_from = ($this->page * $this->per_page) - $this->per_page;
			if ($this->start_from < 0) { $this->start_from = 0; }
		}
	
		// Установка текущей страницы
		private function set_page()
		{
			if (($this->page == '') || ($this->page > $this->pages))
			{
			  $this->page = 1;
			}	
		}
	
		// Генератор страниц
		public function pages_gen()
		{
			// Языковой массив
			$lang_mass = Array(
						   'ru'=>Array('page'=>'Страница', 'from'=>'из'),
						   'en'=>Array('page'=>'Page', 'from'=>'from'),
						   'kz'=>Array('page'=>'Бет', 'from'=>'')
						   );
			// Вывод
			$html = '
			<table width="100%" cellpadding="7" cellspacing="0">
			 <tr>
			  <!--<td width="200">'.$lang_mass[$this->lang]['page'].' <b>'.$this->page.'</b> '.($this->lang!='kz'?''.$lang_mass[$this->lang]['from'].' <b>'.$this->pages.'</b>':'').'</td>-->
			  <td style=" text-align:center">';
			
			# Диапазон
			$diap_index = ceil($this->page / 10);
			$diap_start = (($diap_index * 10)-10)+1;
			$diap_end	= $diap_start + 10;
			
			if (isset($_GET["p"]) && intval($_GET["p"])!='')
			{
				$pred = intval($_GET["p"]) - 1;
				if ($pred<1) { $pred = 1; }
				$html .= '&nbsp;&nbsp;<a class="page_link prev_page" style="margin-right: 0px; padding: 2px 4px;" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=$pred" : "p=$pred").'">&larr;&nbsp;Предыдущая</a>';
			}
			else
			{
				$html .= '&nbsp;&nbsp;<a class="page_link prev_page" style="margin-right: 0px; padding: 2px 4px;" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=1" : "p=1").'">&larr;&nbsp;Предыдущая</a>';	
			}
			
			
			# Если не начало диапозона
			if ($this->page > 10)
			{
				if ($diap_index > 2)
				{
				$html .=  '<a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=1" : "p=1").'">1</a> ... ';
				}
				$html .= '<a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=".($diap_start - 10) : "p=".($diap_start - 10)).'">'.($diap_start - 10).'</a> ...';
			}
			
			# Если страница последняя из диапазона то меняем диапазон
			if ($this->page == $diap_end)
			{
				$diap_start = $diap_start + 10;
				$diap_end   = $diap_end + 10;
			}
			
			for($i=$diap_start; $i<=$diap_end; $i++)
			{
				if ($i <= $this->pages)
				{
					if ($i != $this->page)
					{
						$html .= '&nbsp;&nbsp;<a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=$i" : "p=$i").'">'.$i.'</a>';
						$lastPage = $i;
					}
					else
					{
						$html .= '&nbsp;&nbsp;<span class="page_set">'.$i.'</span>';
						$lastPage = $i;
					}
				}
			}
			
			# Если не конец не деапозона
			if ($this->pages > $diap_end)
			{
				$html .= ' ... <a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=$this->pages" : "p=$this->pages").'">'.$this->pages.'</a>';
			}
			
			if (isset($_GET["p"]) && intval($_GET["p"])!='')
			{
				$sledPage2 = intval($_GET["p"]) + 1;
				if ($sledPage2>$lastPage) { $sledPage = $lastPage; }
				else { $sledPage = $sledPage2; }
				$html .= '&nbsp;&nbsp;<a class="page_link next_page" style="margin-right: 0px; padding: 2px 4px;" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=$sledPage" : "p=$sledPage").'">Следующая&nbsp;&rarr;</a>';
			}
			else
			{
				$html .= '&nbsp;&nbsp;<a class="page_link next_page" style="margin-right: 0px; padding: 2px 4px;" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=2" : "p=2").'">Следующая&nbsp;&rarr;</a>';	
			}
		  
		  
			$html .= '</td>
			 </tr>
			</table>';
		
			return $html;
		}
}

# Класс страниц
class Pagination
{
		private $lang;
		private $url;
		private $query;
		private $elements_count;
		private $per_page;
		private $page;
		
		public $pages;
		public $start_from;
	
		// Установка значений
		public function setvars($lang, $url, $query, $elements_count, $per_page, $page)
		{
			$this->lang				= $lang;
			$this->url 				= $url;
			$this->query			= eregi_replace("&p=[[:digit:]]{1,100}", "", $query);
			$this->query			= eregi_replace("p=[[:digit:]]{1,100}", "", $this->query);
			$this->elements_count 	= intval($elements_count);
			$this->per_page 		= intval($per_page);
			$this->page 			= intval($page);
		
			$this->count_pages();
			$this->set_page();
			$this->set_start();
		}	
	
		// Подсчет страниц
		private function count_pages()
		{
			$this->pages = ceil($this->elements_count / $this->per_page);
		}
	
		// Начинасть с
		private function set_start()
		{
			$this->start_from = ($this->page * $this->per_page) - $this->per_page;
			if ($this->start_from < 0) { $this->start_from = 0; }
		}
	
		// Установка текущей страницы
		private function set_page()
		{
			if (($this->page == '') || ($this->page > $this->pages))
			{
			  $this->page = 1;
			}	
		}
	
		// Генератор страниц
		public function pages_gen()
		{
			// Языковой массив
			$lang_mass = Array(
						   'ru'=>Array('page'=>'Страница', 'from'=>'из'),
						   'en'=>Array('page'=>'Page', 'from'=>'from'),
						   'kz'=>Array('page'=>'Бет', 'from'=>'')
						   );
			// Вывод
			$html = '
			<table width="100%" cellpadding="7" cellspacing="0">
			 <tr>
			  <td width="200">'.$lang_mass[$this->lang]['page'].' <b>'.$this->page.'</b> '.($this->lang!='kz'?''.$lang_mass[$this->lang]['from'].' <b>'.$this->pages.'</b>':'').'</td>
			  <td align="right" style=" text-align:right">';
			
			# Диапазон
			$diap_index = ceil($this->page / 10);
			$diap_start = (($diap_index * 10)-10)+1;
			$diap_end	= $diap_start + 10;
			
			
			# Если не начало диапозона
			if ($this->page > 10)
			{
				if ($diap_index > 2)
				{
				$html .=  '<a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=1" : "p=1").'">1</a> ... ';
				}
				$html .= '<a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=".($diap_start - 10) : "p=".($diap_start - 10)).'">'.($diap_start - 10).'</a> ...';
			}
			
			# Если страница последняя из диапазона то меняем диапазон
			if ($this->page == $diap_end)
			{
				$diap_start = $diap_start + 10;
				$diap_end   = $diap_end + 10;
			}
			
			for($i=$diap_start; $i<=$diap_end; $i++)
			{
				if ($i <= $this->pages)
				{
					if ($i != $this->page)
					{
						$html .= '&nbsp;&nbsp;<a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=$i" : "p=$i").'">'.$i.'</a>';
					}
					else
					{
						$html .= '&nbsp;&nbsp;<span class="page_set">'.$i.'</span>';
					}
				}
			}
			
			# Если не конец не деапозона
			if ($this->pages > $diap_end)
			{
				$html .= ' ... <a class="page_link" href="'.$this->url.'?'.($this->query != '' ? $this->query."&p=$this->pages" : "p=$this->pages").'">'.$this->pages.'</a>';
			}
		  
		  
			$html .= '</td>
			 </tr>
			</table>';
		
			return $html;
		}
}

# Класс строк
class Strings {
	
	public function date($lang, $str, $type_from, $type_to)
	{
		$conv_date ='';
		$lang_mass = Array(
							'ru'=>Array(
										'at'=>'в',
										'mounth'=>Array(
													'01'=>'января',
													'02'=>'февраля',
													'03'=>'марта',
													'04'=>'апреля',
													'05'=>'мая',
													'06'=>'июня',
													'07'=>'июля',
													'08'=>'августа',
													'09'=>'сентября',
													'10'=>'октября',
													'11'=>'ноября',
													'12'=>'декабря')
							),
							'en'=>Array(
										'at'=>'at',
										'mounth'=>Array(
													'01'=>'january',
													'02'=>'february',
													'03'=>'march',
													'04'=>'april',
													'05'=>'may',
													'06'=>'june',
													'07'=>'july',
													'08'=>'august',
													'09'=>'september',
													'10'=>'october',
													'11'=>'november',
													'12'=>'december')
							),
							
							'kz'=>Array(
										'at'=>'',
										'mounth'=>Array(
													'01'=>'қаңтар',
													'02'=>'ақпан',
													'03'=>'наурыз',
													'04'=>'сәуір',
													'05'=>'мамыр',
													'06'=>'маусым',
													'07'=>'шілде',
													'08'=>'тамыз',
													'09'=>'қыркүйек',
													'10'=>'қазан',
													'11'=>'қараша',
													'12'=>'желтоқсан')
							)
						);
			$lang_mass2 = Array(
							'ru'=>Array(
										'at'=>'в',
										'mounth'=>Array(
													'01'=>'январь',
													'02'=>'февраль',
													'03'=>'март',
													'04'=>'апрель',
													'05'=>'май',
													'06'=>'июнь',
													'07'=>'июль',
													'08'=>'август',
													'09'=>'сентябрь',
													'10'=>'октябрь',
													'11'=>'ноябрь',
													'12'=>'декабрь')
							),
							'en'=>Array(
										'at'=>'at',
										'mounth'=>Array(
													'01'=>'january',
													'02'=>'february',
													'03'=>'march',
													'04'=>'april',
													'05'=>'may',
													'06'=>'june',
													'07'=>'july',
													'08'=>'august',
													'09'=>'september',
													'10'=>'october',
													'11'=>'november',
													'12'=>'december')
							),
							
							'kz'=>Array(
										'at'=>'',
										'mounth'=>Array(
													'01'=>'қаңтар',
													'02'=>'ақпан',
													'03'=>'наурыз',
													'04'=>'сәуір',
													'05'=>'мамыр',
													'06'=>'маусым',
													'07'=>'шілде',
													'08'=>'тамыз',
													'09'=>'қыркүйек',
													'10'=>'қазан',
													'11'=>'қараша',
													'12'=>'желтоқсан')
							)
						);
						
		
		# Если из SQL формата
		if ($type_from == 'sql')
		{
			$date_time 	= explode(' ', $str);
			$date 		= explode('-', $date_time[0]);
			$time = explode(':', isset($date_time[1]));
			
			# Обычный тип даты
			if ($type_to == 'date')
			{
				$conv_date = isset($date[2]).'.'.isset($date[1]).'.'.isset($date[0]);
			}
			
			if ($type_to == 'year')
			{
				$conv_date = $date[0];
			}
			
			if ($type_to == 'my')
			{
				$conv_date = $lang_mass2[$lang]['mounth'][$date[1]].' '.$date[0];
			}
			
			# Текстовая дата
			if ($type_to == 'datetext')
			{
				$conv_date = $date[2].' '.$lang_mass[$lang]['mounth'][$date[1]].' '.$date[0];
			}
			
			# Дата и время
			if ($type_to == 'datetime')
			{
				$conv_date = isset($date[2]).'.'.isset($date[1]).'.'.isset($date[0]).' '.$lang_mass[$lang]['at'].' '.isset($time[0]).':'.isset($time[1]);
			}
			
			# Текстовые дата и время
			if ($type_to == 'datetimetext')
			{
				if (substr($date[2], 0, 1) == 0) { $date[2] = substr($date[2], 1); } 
				if (substr($time[0], 0, 1) == 0) { $time[0] = substr($time[0], 1); } 
				$conv_date = $date[2].' '.$lang_mass[$lang]['mounth'][$date[1]].' '.$date[0].' '.$lang_mass[$lang]['at'].' '.$time[0].':'.$time[1];
			}
		}
		
		# Из обычного формата
		if ($type_from == 'date')
		{
			$date_time 	= explode('.', $str);

			# SQL
			if ($type_to == 'sql')
			{
				$conv_date = $date_time[2].'-'.$date_time[1].'-'.$date_time[0];
			}
			
		}
		
		return $conv_date;
	}
	
	
	# Стоимость
	public function coast($str, $act='to')
	{
		$new_str = $str;
		
		# В
		if ($act == 'to')
		{
			if (strlen($str) > 3)
			{
				$u=0;
				$money_coast = '';
				for($i=strlen($str); $i>=0; $i--)
				{
					$money_coast = substr($str, $i, 1).$money_coast;
					if ($u == 3) { $money_coast = ' '.$money_coast; $u=0; }
					$u++;
				}
				
				$new_str = $money_coast;
  			}
		}
		
		# Из
		if ($act == 'from')
		{
			$new_str = eregi_replace(' ', $str);
		}	
		
		return $new_str; 
	}
	
	
	# MIME
	public function mime($str, $data_charset='utf-8', $send_charset='utf-8') 
	{
		if($data_charset != $send_charset) 
		{
			$str = iconv($data_charset, $send_charset, $str);
		}
		
		return '=?' . $send_charset . '?B?' . base64_encode($str) . '?=';
	}
	
	public function pr($i)
    {
        $i=strip_tags($i);
        $i=htmlspecialchars($i, ENT_QUOTES);
		$i=mysql_real_escape_string($i);
        return trim($i);
    }
	
	public function pr2($i)
    {
        $i=htmlspecialchars($i, ENT_QUOTES);
		$i=mysql_real_escape_string($i);
        return trim($i);
    }
	
	// переображения переменных после POST с плюсом
    public function pr_plus($i)
    {
        $i=stripslashes($i);
        //$i=htmlspecialchars_decode($i);
		$i=str_replace('&amp;plus;', '+', $i);
		$i=str_replace('&plus;', '+', $i);
		$i=str_replace('&amp;#039;', "'", $i);
		$i=str_replace('&#039;', "'", $i);
		$i=str_replace('&amp;quot;', '"', $i);
		$i=str_replace('&quot;;', '"', $i);
        return trim($i);
    }
	
	public function diff_days($c_date, $d_date)
	{
		$diff = abs(strtotime($d_date) - strtotime($c_date));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
		$all_days = floor($diff / (60*60*24));
		
		return $all_days;
	}
	
	// переображения переменных до отображение в интерфейс (для html)
	public function change_s($i)
	{
		$i = str_replace('&#34;', '"', $i);
		$i = str_replace('&quot;', '"', $i);
		$i = str_replace('&amp;#039;', "'", $i);
		$i = str_replace('&#039;', "'", $i);
		$i = str_replace('&apos;', "'", $i);
		$i = str_replace('&#043;', "+", $i);
		$i = str_replace('&amp;plus;', "+", $i);
		$i = str_replace('&amp;quot;', '"', $i);
		$i = str_replace('&plus;', "+", $i);
		$i = str_replace('&amp;', "&", $i);
		$i = str_replace(array("\r\n","\n","\r","\t","\\","\b"), "", $i);
		return trim(strip_tags(htmlspecialchars(stripslashes($i))));
	}
	
	public function check_smartphone() 
	{ 
		$phone_array = array('iphone', 'android', 'ipod', 'samsung', 'htc_', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
		$agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

		foreach ($phone_array as $value) {
			if ( strpos($agent, $value) !== false ) return true;
		}
		return false;
	}
	
	public function lower($text)
	{
		$UP_CASE=array('A'=>'a', 'B'=>'b', 'C'=>'c', 'D'=>'d', 'E'=>'e', 'F'=>'f', 'G'=>'g', 'H'=>'h', 'I'=>'i', 'J'=>'j', 'K'=>'k', 'L'=>'l', 'M'=>'m', 'N'=>'n', 'O'=>'o', 'P'=>'p', 'Q'=>'q', 'R'=>'r', 'S'=>'s', 'T'=>'t', 'U'=>'u', 'V'=>'v', 'W'=>'w', 'X'=>'x', 'Y'=>'y', 'Z'=>'z', 'А'=>'а', 'Б'=>'б', 'В'=>'в', 'Г'=>'г', 'Д'=>'д', 'Е'=>'е', 'Ё'=>'ё', 'Ж'=>'ж', 'З'=>'з', 'И'=>'и', 'Й'=>'й', 'К'=>'к', 'Л'=>'л', 'М'=>'м', 'Н'=>'н', 'О'=>'о', 'П'=>'п', 'Р'=>'р', 'С'=>'с', 'Т'=>'т', 'У'=>'у', 'Ф'=>'ф', 'Х'=>'х', 'Ц'=>'ц', 'Ч'=>'ч', 'Ш'=>'ш', 'Щ'=>'щ', 'Ъ'=>'ъ', 'Ы'=>'ы', 'Ь'=>'ь', 'Э'=>'э', 'Ю'=>'ю', 'Я'=>'я');
		 return strtr($text,  $UP_CASE);
	}
	
	public function translit($string)
	{
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => "",   'ы' => 'y',   'ъ' => "",
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
			' ' => '-',	  'ә' => 'a',	'і' => 'i',
			'ң' => 'n',	  'ғ' => 'g',   'ү' => 'u',
			'ұ' => 'u',   'қ' => 'k',   'ө' => 'o',
			'һ' => 'kh',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => "",   'Ы' => 'Y',   'Ъ' => "",
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
			' ' => '-',	  'Ә' => 'A',	'І' => 'I',
			'Ң' => 'N',	  'Ғ' => 'G',   'Ү' => 'U',
			'Ұ' => 'U',   'Қ' => 'K',   'Ө' => 'O',
			'Һ' => 'KH',
		);
		
		$string = str_replace(' ','-',$string);
		$string = preg_replace('/[^a-zа-яё0-9\-]+/iu', '', $string);

		return $this->lower(strtr($string, $converter));
	}
}

// Класс продавцы
class Managers 
{
	public $man_id;
	public $man_block;
	public $man_name;
	public $man_login;	
	
	function __construct() 
	{
		$this->check_auth();
	}
	
	# Генератор сессии
	public function sess_gen()
	{
		return md5(date('smdsiYmH'));
	}
	
	
	# Генератор паролей
	public function pass_gen()
	{
		return substr($this->sess_gen(), 0, 6);
	}
	
	/*** Проверка ИИН - КАЗАХСТАН */
	public function valid_nn($nn)
	{
		$s = 0; 
		for ($i = 0; $i < 11; $i++)  { $s = $s + ($i + 1) * $nn{$i}; } 
		
		$k = $s % 11; 
		if ($k == 10) 
		{ 
			$s = 0; 
			for ($i = 0; $i < 11; $i++) 
			{ 
				$t = ($i + 3) % 11; 
				if($t == 0) { $t = 11; } 
				$s = $s + $t * $nn{$i}; 
			} 
			
			$k = $s % 11; 
			if ($k == 10) 
				return false; 
				
			return ($k == substr($nn,11,1)); 
		} 
		return ($k == substr($nn,11,1)); 
	}
	
	public function check_auth_ip()
    {
		
		$select = mysql_query("SELECT * FROM `i_manager_users` WHERE `active`='1' AND `id`='".intval($_SESSION['manager_id'])."' AND `user_ip`='".$_SERVER['REMOTE_ADDR']."' AND `user_brauser`='".$_SERVER['HTTP_USER_AGENT']."' LIMIT 1");
		
        if (
            isset($_SESSION['manager_id']) &&
            (intval($_SESSION['manager_id']) != '') &&
            (mysql_num_rows($select)) == 1)
        {			
            return true;

        } else {
            return false;
        }
    }
	
	# Проверка юзера по сессии
	public function check_auth()
	{
		if (
			isset($_SESSION['manager_id']) && 
			(intval($_SESSION['manager_id']) != '')  && 			
			(mysql_num_rows(mysql_query("SELECT `id` FROM `i_manager_users` WHERE `active`='1' AND `id`='".intval($_SESSION['manager_id'])."' LIMIT 1")) == 1)
			)
		{
			# Переменные
			$manager_id = intval($_SESSION['manager_id']);
		
			# Получаем данные пользователя
			$manager_name = mysql_result(mysql_query("SELECT `name` FROM `i_manager_users` WHERE `id`='".$manager_id."' LIMIT 1"), 0);			
			$manager_block = mysql_result(mysql_query("SELECT `id_section` FROM `i_manager_users` WHERE `id`='".$manager_id."' LIMIT 1"), 0);
			$manager_login = mysql_result(mysql_query("SELECT `login` FROM `i_manager_users` WHERE `id`='".$manager_id."' LIMIT 1"), 0);
			
			# Обновляем данные
			mysql_query("UPDATE `i_manager_users` SET `timestamp_x`=NOW() WHERE `id`='".$manager_id."' LIMIT 1");
		  
			# Устанавливаем данные в класс
			$this->man_id		= $manager_id;
			$this->man_name 	= $manager_name;
			$this->man_block 	= $manager_block;
			$this->man_login 	= $manager_login;
		
			return true;
		
		} else {
		  return false;
		}
	}
	
	# Авторизация пользователя
	public function login_user($login, $pass)
	{
		# Если авторизация верна
		if ($this->check_login($login, $pass) == true)
		{
			# Получаем данные пользователя			
			list($manager_id, $manager_name, $manager_block) = mysql_fetch_row(mysql_query("SELECT `id`, `name`, `id_section` FROM `i_manager_users` WHERE `active`='1' AND `login`='".addslashes($login)."' AND `pass`='".addslashes($pass)."' LIMIT 1"));
			
			# Генерируем сессию
			$manager_new_sess = $this->sess_gen();
			
			# Обновляем сессию
			mysql_query("UPDATE `i_manager_users` SET `timestamp_x`=NOW(), `sess`='".$manager_new_sess."', `user_ip`='".$_SERVER['REMOTE_ADDR']."', `user_brauser`='".$_SERVER['HTTP_USER_AGENT']."' WHERE `id`='".$manager_id."' LIMIT 1");						
			
			# Устанавливаем данные в класс
			$this->man_id		= $manager_id;
			$this->man_name 	= $manager_name;
			$this->man_block 	= $manager_block;
			$this->man_login 	= $login;

			# Устанавливаем данные в сессию
			$_SESSION['manager_id']	 = $manager_id;
			$_SESSION['manager_sess'] = $manager_new_sess;
		}
	}
	
	
	# Выход пользователя
	public function logout_user()
	{
		# Если авторизован
		if ($this->check_auth() == true)
		{
			# Обновляем сессию
			//mysql_query("UPDATE `i_manager_users` SET `timestamp_x`=NOW(), `sess`='".$this->sess_gen()."' WHERE `id`='".$this->man_id."' LIMIT 1");
			mysql_query("UPDATE `i_manager_users` SET `timestamp_x`=NOW(), `sess`=NULL WHERE `id`='".$this->man_id."' LIMIT 1");
		
			# Устанавливаем данные в класс
			$this->man_id		= '';
			$this->man_name 	= '';
			$this->man_block 	= '';
			$this->man_login 	= '';

			
			# Устанавливаем данные в сессию			
			$_SESSION['manager_id']	 = '';
			$_SESSION['manager_sess'] = '';
			
			return true;
			
		} else {
		  return false;
		}	
	}	
	
	# Проверка данных авторизации
	public function check_login($login, $pass)
	{
		if (($login != '') && ($pass != '') && (mysql_num_rows(mysql_query("SELECT `id` FROM `i_manager_users` WHERE `active`='1' AND `login`='".$login."' AND `pass`='".$pass."' LIMIT 1")) == 1))
		{
		  return true;
		} else {
		  return false;
		}
	}	
	
	public function check_block($login, $pass)
	{
		if (($login != '') && ($pass != '') && (mysql_num_rows(mysql_query("SELECT `id` FROM `i_manager_users` WHERE `active`='0' AND `login`='".$login."' LIMIT 1")) == 1))
		{
		  return true;
		} else {
		  return false;
		}
	}	
	
	// проверка авторизации
    public function pr_cookieM()
    {
        $elements = explode("|", @$_COOKIE['man_auth_site']);
		
		$yes = 0;
		// Продавцы
        $select = mysql_query("SELECT `id`, `pass` FROM `i_manager_users` WHERE `active`='1' AND `login`='".$elements['0']."' LIMIT 1");
        if (mysql_num_rows($select) == 1)
		{
            $res = mysql_fetch_array($select);
            if (sha1($res['pass']) == @$elements['1']) 
			{
				$_SESSION['manager_id'] = $res['id'];
				$_SESSION['manager_sess'] = @$res['sess'];
				$yes = 1;
            }
        }							
    }  			
}
?>