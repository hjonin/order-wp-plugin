<?php

function _ajax_devis() {
    // check for security token
    //check_ajax_referer('ajax_devis_nonce', 'security');

    // get all data from post
    $devisName = wp_strip_all_tags($_POST['devis_name']);
    $devisEmail = sanitize_email($_POST['devis_email']);
    $devisSummary = $_POST['devis_summary'];
    $devisNotes = nl2br(stripslashes(wp_kses($_POST['devis_notes'], $GLOBALS['allowedtags'])));
    $devisDate = $_POST['devis_date'];
    $devisCopy = $_POST['devis_copy'];

    /** Start of config strings */
    $to = get_option( 'admin_email' ); // Recipient of the request is wordpress admin
    $subject = 'Traiteur bebop commande pour le ' . $devisDate; // Subject for the E-mail

    // default messages
    $greetings = 'Bonjour/Bonsoir,';
    $request = 'Vous avez reçu une demande de devis pour la commande suivante :';
    $ending = 'Cordialement.';
    $ps = 'Notes du client :';
    /** End of config strings */

    $style = 	'<style type="text/css">' .
            'table {' .
            '	font-family:Arial, Helvetica, sans-serif;' .
            '	color:#666;' .
            '	font-size:12px;' .
            '	text-shadow: 1px 1px 0px #fff;' .
            '	background:#eaebec;' .
            '	margin:20px;' .
            '	border:#ccc 1px solid;' .
            '	-moz-border-radius:3px;' .
            '	-webkit-border-radius:3px;' .
            '	border-radius:3px;' .
            '	-moz-box-shadow: 0 1px 2px #d1d1d1;' .
            '	-webkit-box-shadow: 0 1px 2px #d1d1d1;' .
            '	box-shadow: 0 1px 2px #d1d1d1;' .
            '}' .
            'table th {' .
            '	padding:21px 25px 22px 25px;' .
            '	border-top:1px solid #fafafa;' .
            '	border-bottom:1px solid #e0e0e0;' .
            '	background: #ededed;' .
            '	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));' .
            '	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);' .
            '}' .
            'table th:first-child {' .
            '	text-align: left;' .
            '	padding-left:20px;' .
            '}' .
            'table tr:first-child th:first-child {' .
            '	-moz-border-radius-topleft:3px;' .
            '	-webkit-border-top-left-radius:3px;' .
            '	border-top-left-radius:3px;' .
            '}' .
            'table tr:first-child th:last-child {' .
            '	-moz-border-radius-topright:3px;' .
            '	-webkit-border-top-right-radius:3px;' .
            '	border-top-right-radius:3px;' .
            '}' .
            'table tr {' .
            '	text-align: center;' .
            '	padding-left:20px;' .
            '}' .
            'table td:first-child {' .
            '	text-align: left;' .
            '	padding-left:20px;' .
            '	border-left: 0;' .
            '}' .
            'table td {' .
            '	padding:18px;' .
            '	border-top: 1px solid #ffffff;' .
            '	border-bottom:1px solid #e0e0e0;' .
            '	border-left: 1px solid #e0e0e0;' .
            '	background: #fafafa;' .
            '	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));' .
            '	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);' .
            '}' .
            'table tr.even td {' .
            '	background: #f6f6f6;' .
            '	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));' .
            '	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);' .
            '}' .
            'table tr:last-child td {' .
            '	border-bottom:0;' .
            '}' .
            'table tr:last-child td:first-child {' .
            '	-moz-border-radius-bottomleft:3px;' .
            '	-webkit-border-bottom-left-radius:3px;' .
            '	border-bottom-left-radius:3px;' .
            '}' .
            'table tr:last-child td:last-child {' .
            '	-moz-border-radius-bottomright:3px;' .
            '	-webkit-border-bottom-right-radius:3px;' .
            '	border-bottom-right-radius:3px;' .
            '}' .
            'table tr:hover td {' .
            '	background: #f2f2f2;' .
            '	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));' .
            '	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	' .
            '}' .
            '</style>';

    // set header
    $headers = array();
    $headers[] = 'FROM: ' . $devisName . ' <' . $devisEmail . '>';
    if ($devisCopy == "true") {
        array_push($headers, 'Cc: ' . $devisEmail);
    }

    // set content type html (default is text, and does not permit html rendering)
    add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));

    // define the mail content
    $mailContent = 	'<html>' .
            '<head>' .
            $style .
            '</head>' .
            '<body>' .
            $greetings . '<br/><br/>' . $request . '<br/><br/>' .
            $devisSummary .
            '<br/><br/>' .$ending .
            (($devisNotes != '') ? '<br/><br/>' . $ps . '<br/>' . $devisNotes : '') .
            '</body>' .
            '</html>';

    // send mail (first parameter is the $to parameter)
    if (wp_mail($to, $subject, $mailContent, $headers)) {
        wp_send_json('success');
    }
    else {
        wp_send_json('error');
    }
}