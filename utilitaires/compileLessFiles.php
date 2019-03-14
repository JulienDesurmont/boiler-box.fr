<?php
require "./system/lessc.inc.php";
$less = new lessc;

try {
	/* Concatenation des fichiers */
	$contentCommun = file_get_contents("toBeCompiled/lessFiles/commun.less");
	$contentCommunAll = file_get_contents("toBeCompiled/lessFiles/commun_all.less");

	file_put_contents("toBeCompiled/lessFiles/boilerbox.less", $contentCommun, FILE_APPEND);
	file_put_contents("toBeCompiled/lessFiles/boilerbox.less", $contentCommunAll, FILE_APPEND);

    file_put_contents("toBeCompiled/lessFiles/boilerbox_print.less", $contentCommun, FILE_APPEND);
    file_put_contents("toBeCompiled/lessFiles/boilerbox_print.less", $contentCommunAll, FILE_APPEND);

    file_put_contents("toBeCompiled/lessFiles/login.less", $contentCommunAll, FILE_APPEND);

	
	$less->compileFile("toBeCompiled/lessFiles/boilerbox.less", "toBeCompiled/cssFiles/boilerbox.css");
	$less->compileFile("toBeCompiled/lessFiles/boilerbox_print.less", "toBeCompiled/cssFiles/boilerbox_print.css");
	$less->compileFile("toBeCompiled/lessFiles/login.less", "toBeCompiled/cssFiles/login.css");

	copy("toBeCompiled/cssFiles/boilerbox.css", "../web/css/boilerbox.css");
	copy("toBeCompiled/cssFiles/boilerbox_print.css", "../web/css/boilerbox_print.css");
    copy("toBeCompiled/cssFiles/login.css", "../web/css/login.css");

	echo "Fin de compilation des fichiers";
} catch (exception $e) {
	echo "Erreur de compilation: ".$e->getMessage();
}
?>
