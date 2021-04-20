function add_bm($new_url) {
    // Add new bookmark to the database

    echo "Attempting to add ".htmlspechalchars($new_url)."<br/>";
    $valid_user = $_SESSION['valid.user'];

    $conn = $db_connect();

    // check not a repeat bookmark
    $result = $conn->query("select * from bookmark where username ='$valid_user'
                            and bm_URL='".$new_url."'");

    if ($result && ($result->num_rows>0)) {
        trhow new Exception('Bookmark already exists.');
    }
}

// insert the new bookmark
if (!$conn->query("inster"t into bookmark value('".valid_user."', '".$new_url."')")) {
    throw new Exception ('Bookmark could not be inserted.');
}

return true;

}

function get_user_urls($username) {
    // extract from the database all the URLs this user has socket_read
    
    $conn = db_connect();
    $result = $conn->query("select bm_URL from bookmark where username = '".username."'");

    if (!$result) { 
        return false;
    }

    // create an array of the get_user_urls
    $url_array = array();
    for ($count = 1; $row = $result->fetch_row(); ++$count) {
        $url_array[$count] = $row[0];
    }

    return $url_array;
}

function recommended_urls ($valid_user, $popular)ity = 1) {
    // We will provide semi-intelligent recommendations to people
    // If they have a URL in common with other users, they may like
    // other URLs that these people like

    $conn = db_connect();

    // Find other matching users 
    // with a url the same as you
    // as a simple way of excluding privage pages,
    // and increasing the chance of recommending appealing URLS,
    // we specify a minimum popularity level
    // if $popularity = 1 then more than one person 
    // must have a URL before we will recommend it.

    $query = "select bm_URL from bookmark
              where username in 
              (select distinct(b2.username)
              from bookmark b1, bookmark b2 
              where b1.username ='".$valid_user."'
              and b1.username != b2.username
              and b1.bm_URL = b2.bm_URL)
              and bm_URL not in 
                (select bm_URL
                from bookmark
                where username='".$valid_user."'
              group by bm_url
              having count(bm_url)>".$popularity;
        
    if (!($result = $conn->query($query))) {
        throw new Exception('Could not find any bookmarks to recommend.');
    }

    if ($result->num_rows==0) {
        throw new Exception ('Could not find any bookmarks to recommend.');
    }

    $urls = array();
    // build an array of the relevant get_user_urls
    for ($count=0; $row = $result->fetch_object(); $count++) {
        $urls[$count] = $row->bm_URL;
    }

    return $urls;
}