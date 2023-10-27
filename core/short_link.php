<?php

namespace LinkManager {

    use DB\DBCLS;

    function getShortLink($link)
    {
        $code = "";
        $db = new DBCLS();
        $link = filter_var($link, FILTER_SANITIZE_URL);
        if (!filter_var($link, FILTER_VALIDATE_URL)) {
            http_response_code(404);
            return null;
        }
        $result = $db->query('SELECT * FROM `links` WHERE `targetlink` = ?', [$link])->get_result();
        if ($result->num_rows == 0) {
            $code = $db->query('SELECT `code` FROM `links` ORDER BY `code` DESC LIMIT 1')
                ->get_result();
            if ($code->num_rows == 0) {
                $code = "aaab";
            } else {
                $code = $code->fetch_assoc()['code'];
                $code++;
            }
            $db->query('INSERT INTO `links` (`code`, `targetlink`) VALUES (?, ?)', [$code, $link]);
        } else {
            $code = $result->fetch_assoc()['code'];
        }
        if ($code == "") {
            http_response_code(500);
            return null;
        }
        header('Content-type: application/text');
        return  $code;
    }
    function getTargetLink($code)
    {
        $db = new DBCLS();
        $result = $db->query('SELECT * FROM `links` WHERE `code` = ?', [$code])->get_result();
        if ($result->num_rows == 0) {
            return null;
        }
        $db->query('UPDATE `links` SET `totalhit` = `totalhit` + 1 WHERE `code` = ?', [$code]);

        return $result->fetch_assoc()['targetlink'];
    }
}
