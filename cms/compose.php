<?php
require 'db.inc.php';
include 'header.inc.php';

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD) or
	die('Unable to connect. Check your connection parameters.');
mysql_select_db(MYSQL_DB, $db) or die(mysql_error($db));

$action = (isset($_GET['action'])) ? $_GET['action'] : '';
$article_id = (isset($_GET['article_id']) && ctype_digit($_GET['article_id'])) ? $_GET['article_id'] : '';
$title = (isset($_POST['title'])) ? $_POST['title'] : '';
$article_text = (isset($_POST['article_text'])) ? $_POST['article_text'] : '';
$user_id = (isset($_POST['user_id'])) ? $_POST['user_id'] : '';

if ($action == 'edit' && !empty($article_id)) {
	$sql = 'SELECT
			title, article_text, user_id
		FROM 
			cms_articles
		WHERE
			article_id = ' . $article_id;
	$result = mysql_query($sql, $db) or die(mysql_error($db));
	
	$row = mysql_fetch_assoc($result);
	extract($row);
	mysql_free_result($result);
}
?>
<h2>Compose Article</h2>
<form method="POST" action="transact_article.php">
	<table>
		<tr>
			<td><label for="title">Title:</label></td>
			<td><input type="text" name="title" id="title" maxlength="255" value="<?php echo htmlspecialchars($title); ?>"/></td>
		</tr><tr>
			<td><label for="article_text">Text:</label></td>
			<td><textarea name="article_text" rows="10" cols="60"><?php echo htmlspecialchars($article_text); ?></textarea></td>
		</tr><tr>
			<td> </td>
			<td>
<?php
				if ($_SESSION['access_level'] < 2) {
					echo '<input type="hidden" name="user_id" value="' . $user_id . '"/>';
				}
				if (empty($article_id)) {
					echo '<input type="submit" name="action" value="Submit New Article"/>';
				} else {
					echo '<input type="hidden" name="article_id" value="' . $article_id . '"/>';
					echo '<input type="submit" name="action" value="Save Changes"/>';
				}
?>
			</td>
		</tr>
	</table>
</form>
<?php
require_once 'footer.inc.php';
?>
