<!DOCTYPE html>
<html>
<head>
    <title>Dictionary test VocativePolishFirstName</title>
    <meta charset="UTF-8">
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .tg {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .tg td {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 10px 5px;
            border-style: solid;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
        }

        .tg th {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
            padding: 10px 5px;
            border-style: solid;
            border-width: 1px;
            overflow: hidden;
            word-break: normal;
        }

        .tg .ok {
            vertical-align: top
        }

        .tg .error {
            color: #fe0000;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php
//debug
/*
echo 'Mariola: ' . in_array(substr('Mariola', -4, 4), array("aja", "rola"));
echo '<br />';
echo substr('Mariola', 0, -1) . "u";
echo '<br />';
echo '<br />';
*/
?>
<h3>Test first name:</h3>

<form method="post" action="?form">
    <div>
        <input name="firstname" type="text" value=""/> <input type="submit" name="submit" value="Submit"/>
    </div>
</form>
<?php
require '../src/VocativePolishFirstName.php';

if (isset($_GET['form']) && !empty($_POST['firstname'])) {
    $input = $_POST['firstname'];
    $test = new \ecbox\VocativePolishFirstName($input);
    echo '<strong>' . $test->getVocativeString() . '</strong>';
}
//phpinfo();
?>
<br/>

<h3>Tests from dictionary with first names:</h3>

<a href="<?php echo $_SERVER["DOCUMENT_URI"]; ?>">Show all</a>
<br/>by differences:
<a href="?diff">all diff</a> |
<a href="?diff=M">male</a> |
<a href="?diff=W">woman</a> |
<a href="?diff=U">unknowns</a>
<br/>by genders:
<a href="?gender=M">male</a> |
<a href="?gender=W">woman</a>
<br/>
<br/>
<table class="tg">
    <tr>
        <th class="ok">Input</th>
        <th class="ok">Dictionary vocative</th>
        <th class="ok">Remade to vocative</th>
        <th class="ok">Gender</th>
        <th class="ok">Detected gender</th>
    </tr>
    <?php
    $showDiffOnly = false;

    if (isset($_GET['diff'])) {
        $showDiffOnly = true;
        $diffType = $_GET['diff'];
    }

    if (isset($_GET['gender'])) {
        $genderType = $_GET['gender'];
    }
    // check with http://odmiana.net/

    # Open the File.
    if (($handle = fopen("imiona.csv", "r")) !== false) {
        # Set the parent multidimensional array key to 0.
        $nn = 1; //total count
        $dd = 1; //diff count
        $uu = 1; //unknown count
        while (($data = fgetcsv($handle, 10000, ";")) !== false) {
            # Count the total keys in the row.
            $c = count($data);

            $diff = false;
            $unknown = false;

            $input = $data[0];
            $patternName = $data[1];
            $patternGender = $data[2];

            $v = new \ecbox\VocativePolishFirstName($input);
            $vocativeFirstName = $v->getVocativeFirstName();
            $vocativeArray = $v->getVocativeArray();
            $vocativeGender = $vocativeArray[0];

            $html = '';
            $html .= "<tr>";
            $html .= "<td class='ok'> $input</td> ";
            $html .= "<td class='ok'> $patternName</td> ";

            if ($patternName != $vocativeFirstName) {
                $html .= "<td class='error' > " . $vocativeFirstName . "</td> ";
                $diff = true;
            } else {
                $html .= "<td class='ok' > " . $vocativeFirstName . "</td> ";
            }
            $html .= "<td class='ok' > $patternGender</td> ";
            if ($patternGender != $vocativeGender) {
                $html .= "<td class='error' > " . $vocativeGender . "</td> ";
                $diff = true;
                $unknown = true;
            } else {
                $html .= "<td class='ok' > " . $vocativeGender . "</td> ";
            }

            $html .= "</tr> ";

            if (!$diff && $showDiffOnly) {
                $html = '';
            }

            if (!empty($diffType) && $diffType != $vocativeGender) {
                $html = '';
            }

            if (!empty($genderType) && $genderType != $vocativeGender) {
                $html = '';
            }

            echo $html;

            if ($diff) {
                $dd++;
            }

            if ($unknown) {
                $uu++;
            }

            $nn++;
        }
        # Close the File.
        fclose($handle);
    }
    ?>
</table>
<br />
<h2>Raport</h2>
<?php
echo 'Total dictionary names: ' . $nn . " <br />\n";
echo 'Differences: ' . $dd . " <br />\n";
echo 'Unknowns: ' . $uu . " <br />\n";
echo 'The percentage of errors: ' . round(($dd / $nn) * 100) . '%' . " <br />\n";
?>
<br />
<br />
</body>
</html>
