<?php
/**
 * Convert all dates from a text
 * 
 * PHP Version 5.3.10
 * 
 * @category TAL
 * @package  TAL
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  GNU General Public License http://www.gnu.org/licenses/gpl.html
 * @link     http://rudloff.pro
 * */
setlocale(LC_TIME, "fr_FR.utf8"); 
if (isset($_POST["string"])) {
    $string=$_POST["string"];
    $format=$_POST["format"];
    preg_match_all(
        "|[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]|",
        $string,
        $result, PREG_SET_ORDER
    );
    foreach ($result as $match) {
        $string=str_replace(
            $match[0], strftime($format, strtotime($match[0])), $string
        );
    }
} else {
    $string="";
}
?>
<!Doctype HTML>
<html>
<head>
<meta charset="UTF-8" />
<title>Convertisseur de dates</title>
</head>
<body>
<form method="POST">
    <p>Les dates au format <i>2012-01-01</i> seront converties.</p>
<textarea id="string" name="string" cols="100" rows="25" required>
<?php print($string) ?>
</textarea>
<br/>
<label for="format">Format :</label>
<select id="format" name="format">
<option value="%e %B %Y">1 janvier 2012</option>
<option value="{{date|%e|%B|%Y}}">{{date|1|janvier|2012}}</option>
</select>
<br/>
<input type="submit" />
</form>
</body>
</html>
