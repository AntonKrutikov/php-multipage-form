<?php
	
function url_for($script_path) {
	if($script_path[0] != '/'){
		$script_path = "/" . $script_path;
	}
	return WWW_ROOT.$script_path;
}

function h($string="") {
  return htmlspecialchars($string);
}

function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function displayForm($fields) {
	foreach ($fields as $f => $opt) {
		$validate_error = isset($_SESSION['errors'][$f]) ? $_SESSION['errors'][$f] : '';
		$last_value = isset($_SESSION['values'][$f]) ? $_SESSION['values'][$f] : '';
        if ($opt['type'] == 'text') {
          $template = <<<EOL
          <label for="$f">{$opt['header']}</label><span class="error">$validate_error</span>
          <input type="text" id="$f" name="$f" placeholder="{$opt['placeholder']}" value="$last_value">      
          EOL;
        } else if ($opt['type'] == 'radio') {
          $template = <<<EOL
                <label for="status">{$opt['header']}</label><span class="error">$validate_error</span>
                <div class="radio-group">
                EOL;
          foreach ($opt['variants'] as $v) {
            $template .= "<input type='radio' name='$f' value='{$v['value']}'";
            if ($last_value == $v['value']) {
              $template .= "checked";
            }
            $template .= "> {$v['header']}";
          }
          $template .= '</div>';
        }
        echo ($template);
      }
}
