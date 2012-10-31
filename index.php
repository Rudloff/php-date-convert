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
date_default_timezone_set("Europe/Paris");
$months=array(
    "janvier"=>1,
    "février"=>2,
    "mars"=>3,
    "avril"=>4,
    "mai"=>5,
    "juin"=>6,
    "juillet"=>7,
    "août"=>8,
    "septembre"=>9,
    "octobre"=>10,
    "novembre"=>11,
    "décembre"=>12
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
                $match[0], $match[3]."-".$months[$match[2]]."-".$match[1]
            );
            break;
        default :
            $dates[]=array($match[0], $match[0]);
        }
    }
    return $dates;
}
if (isset($_POST["string"])) {
    $dates=array();
    $string=$_POST["string"];
    $format=$_POST["format"];
    $regexps=array(
        array("|\d\d\d\d-\d\d-\d\d|", "us"),
        array("|(\d\d?)\h(\w*)\h(\d\d\d\d)|u", "fr"),
        array("|(\d\d?)/(\d\d?)/(\d\d\d\d)|", "frnum")
    ); 
    foreach ($regexps as $regexp) {
        $dates=array_merge(
            $dates, getDates(
                $regexp, $string
            )
        );
    }
    ?>
    <table>
        <tr><th></th><th>Jour</th><th>Mois</th><th>Année</th></tr>
    <?php
    foreach ($dates as $date) {
        $info=(getdate(strtotime($date[1])));
        ?>
        <tr>
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
    <?php
    /*$string=str_replace(
            $match[0], strftime($format, strtotime($match[0])), $string
        );*/
} else {
    $string="";
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
    <?php
}
?>
