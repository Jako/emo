<?php
/**
 * Setting Lexicon Entries for emo
 *
 * @package emo
 * @subpackage lexicon
 */

$_lang['setting_emo.adresses_tpl'] = 'Adressen-Chunk';
$_lang['setting_emo.adresses_tpl_desc'] = 'Name eines Chunks, der das JavaScript für die verschlüsselten Adressen am Ende des Bodys enthält.';
$_lang['setting_emo.css_path'] = 'Pfad zum emo-CSS';
$_lang['setting_emo.css_path_desc'] = 'Wenn diese Einstellung gesetzt ist, wird am Ende des Head-Bereichs ein Link zu dieser CSS-Datei eingefügt.';
$_lang['setting_emo.debug'] = 'Debug';
$_lang['setting_emo.debug_desc'] = 'Debug-Informationen als HTML-Kommentar am Ende des Seitenquelltexts ausgeben und das unkomprimierte emo-JavaScript laden.';
$_lang['setting_emo.include_scripts'] = 'emo-JavaScript einbinden';
$_lang['setting_emo.include_scripts_desc'] = 'Wenn diese Einstellung aktiviert ist, wird am Ende des Body-Abschnitts ein Link zum emo-JavaScript (siehe emo.js_path) eingefügt.';
$_lang['setting_emo.js_path'] = 'Pfad zum emo-JavaScript';
$_lang['setting_emo.js_path_desc'] = 'Mit dieser Einstellung kann ein anderer Speicherort für das emo-JavaScript als {assets_url}components/emo/js/emo.min.js angegeben werden.';
$_lang['setting_emo.no_script_message'] = 'Meldung ‚Kein JavaScript‘ (Ressourcen-ID für internen Link)';
$_lang['setting_emo.no_script_message_desc'] = 'Alle E-Mail-Adressen im aktuellen Dokument werden durch den Text in dieser Einstellung ersetzt. Wenn der Text numerisch ist, wird ein Link zu der Ressource mit dieser ID und dem Seitentitel dieser Ressource erzeugt. Wenn die Einstellung leer ist, wird der Standard-Lexikoneintrag verwendet.';
$_lang['setting_emo.selection_range'] = 'Kommagetrennte Liste von aktivierten/deaktivierten Ressourcen-IDs für emo';
$_lang['setting_emo.selection_range_desc'] = 'Fügen Sie eine kommagetrennte Liste mit Ressourcen-IDs ein, die das Plugin (nicht) bearbeiten soll.';
$_lang['setting_emo.selection_type'] = 'Auswahltyp für aktivierte/deaktivierte Ressourcen für emo';
$_lang['setting_emo.selection_type_desc'] = 'Wenn nur einige Ressourcen durch das Plugin bearbeitet werden sollen, ändern Sie den Wert dieser Einstellung auf `include`.';
$_lang['setting_emo.tpl_only'] = 'Keine Ressourcen mit leerer Vorlage bearbeiten';
$_lang['setting_emo.tpl_only_desc'] = 'Aktivieren Sie diese Einstellung, wenn das Plugin nicht auf Ressourcen mit der Vorlage `(blank)` arbeiten soll.';
