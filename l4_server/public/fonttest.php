<?php
$text = "ὅστις";
$fonts = ["Palatino Linotype", "Gentium", "Arial Unicode MS", "ALPHABETUM Unicode", "TITUS Cyberbit Basic", "Code2000", "New Athena Unicode", "Vusillus", "Lucida Grande", "Vusillus Old Face Italic", "Everson Mono Unicode", "Lucida Sans Unicode", "sans-serif"];
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        td {
            padding:10px;
        }
    </style>
</head>
<body>
<form>
    <p>
        <a href="https://lrc.la.utexas.edu/eieol/ntgol/50#grammar_932" target="_blank">EIEOL usage</a>
    </p>
    Text to test: <?php echo $text; ?>
    <table border="1">
        <tr>
            <th>Text</th>
            <th>Text (italic)</th>
            <th>Font requested (generic font if not found)</th>
        </tr>
        <?php foreach ($fonts as $ix=>$font) { ?>
        <tr>
            <td style="font-family:'<?php echo $font; ?>'"><?php echo $text; ?></td>
            <td style="font-style:italic;font-family:'<?php echo $font; ?>'"><?php echo $text; ?></td>
            <td><?php echo $font; ?></td>
        </tr>
        <?php } ?>
    </table>
</form>
</body>
</html>