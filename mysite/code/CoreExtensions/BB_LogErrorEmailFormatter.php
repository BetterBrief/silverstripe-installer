<?php

class BB_LogErrorEmailFormatter extends SS_LogErrorEmailFormatter {

	function format($event) {
		$array = parent::format($event);
		$subject = $array['subject'];
		$data = $array['data'];

		$data .= '<div style="border: 5px green solid"><p style="color: white; background-color: green; margin: 0">Server vars</p><table>';
		foreach ($_SERVER as $key => $var) {
			if ($key == 'HTTP_COOKIE') {
				$var = str_replace('; ',";\n",$var);
			}
			$data .= '<tr><td><b>' . $key . '</b></td><td>' . Debug::text($var) . '</td></tr>';
		}
		$data .= '</table></div>';

		if (isset($_POST) && count($_POST)) {
			$data .= '<div style="border: 5px green solid"><p style="color: white; background-color: green; margin: 0">Post vars</p><table>';
			foreach ($_POST as $key => $var) {
				$data .= '<tr><td><b>' . $key . '</b></td><td>' . Debug::text($var) . '</td></tr>';
			}
			$data .= '</table></div>';
		}

		if (isset($_FILES) && count($_FILES)) {
			$data .= '<div style="border: 5px green solid"><p style="color: white; background-color: green; margin: 0">Files vars</p><table>';
			foreach ($_POST as $key => $var) {
				$data .= '<tr><td><b>' . $key . '</b></td><td>' . Debug::text($var) . '</td></tr>';
			}
			$data .= '</table></div>';
		}

		return array('subject' => $subject,'data' => $data);
	}

}

