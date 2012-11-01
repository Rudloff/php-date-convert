<!Doctype HTML>
    <html>
    <head>
    <meta charset="UTF-8" />
    <title>Convertisseur de dates</title>
    </head>
    <body>
<?php
/**
 * Convert all dates from a text
 * Warning: you must use a 64 bit OS to parse dates prior to 1901
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
date_default_timezone_set("Europe/Paris");
$months=array(
    "janvier"=>1,
    "février"=>2,
    "fevrier"=>2,
    "mars"=>3,
    "avril"=>4,
    "mai"=>5,
    "juin"=>6,
    "juillet"=>7,
    "août"=>8,
    "aout"=>8,
    "septembre"=>9,
    "octobre"=>10,
    "novembre"=>11,
    "décembre"=>12,
    "decembre"=>12
);
/**
 * Retrieve dates in a string
 * 
 * @param string $format Regexp to use
 * @param string $string String to parse
 * 
 * @return array
 * */
function getDates ($format, $string)
{
    global $months;
    $dates=array();
    preg_match_all(
        $format[0],
        $string,
        $result, PREG_SET_ORDER
    );
    foreach ($result as $match) {
        switch ($format[1]) {
        case "frnum" :
            $dates[]=array($match[0], $match[3]."-".$match[2]."-".$match[1]);
            break;
        case "fr" :
            $dates[]=array(
                $match[0], $match[3]."-".$months[$match[2]]."-".intval($match[1])
            );
            break;
        default :
            $dates[]=array($match[0], $match[0]);
        }
    }
    return $dates;
}
if (isset($_POST["format"])) {
    $string = urldecode($_POST["string"]);
    foreach ($_POST as $var=>$value) {
        if ($value=="on") {
            $var=unserialize(urldecode($var));
            $string=str_replace(
                $var[0], strftime($_POST["format"], strtotime($var[1])), $string
            );
        }
    }
    ?>
    <label for="string">Résultat :</label><br/>
    <textarea readonly autofocus id="string" cols="100" rows="25"><?php print(
        $string
    ); ?></textarea>
    <br/>
    <a href="index.php">Recommencer</a>
    <?php
} else if (isset($_POST["string"])) {
    $dates=array();
    $string=$_POST["string"];
    $regexps=array(
        array("|\d\d\d\d-\d\d-\d\d|", "us"),
        array("|(\d\d?e?r?)\h(\w*)\h(\d\d\d\d)|u", "fr"),
        array("|(\d\d?)/(\d\d?)/(\d\d\d\d)|", "frnum")
    ); 
    foreach ($regexps as $regexp) {
        $dates=array_merge(
            $dates, getDates(
                $regexp, $string
            )
        );
    }
    if (empty($dates)) {
        ?>
        <p>Aucune date trouvée</p>
        <a href="index.php">Recommencer</a>
        <?php
    } else {
        ?>
        <form method="POST">
        <table>
            <tr><th></th><th></th><th>Jour</th><th>Mois</th><th>Année</th></tr>
        <?php
        foreach ($dates as $date) {
            $info=(getdate(strtotime($date[1])));
            ?>
            <tr>
                <td>
                <?php
                    print(
                        "<input type='checkbox' checked='checked' name='".
                        urlencode(serialize($date))."' />"
                    );
                ?>
                </td>
                <th>
            <?php
            print($date[0]);
            ?>
            </th>
            <td>
            <?php
            print($info["mday"]);
            ?>
            </td>
            <td>
            <?php
            print($info["month"]." (".$info["mon"].")");
            ?>
            </td>
            <td>
            <?php
            print($info["year"]);
            ?>
            </td>
            </tr>
            <?php
        }
        ?>
        </table>
        <label for="format">Format :</label>
        <select id="format" name="format">
        <option value="%e %B %Y">1 janvier 2012</option>
        <option value="{{date|%e|%B|%Y}}">{{date|1|janvier|2012}}</option>
        </select>
        <?php
            print(
                "<input type='hidden' name='string' value='".
                urlencode($string)."' />"
            );
        ?>
        <input type="submit" />
        </form>
        <?php
    }
} else {
    ?>
    <form method="POST">
        <label for="string">Entrez le texte à analyser :</label><br/>
    <textarea id="string" name="string" cols="100" rows="25" required></textarea>    
    <br/>
    <input type="submit" />
    </form>
    <?php
}
?>
</body>
</html>
