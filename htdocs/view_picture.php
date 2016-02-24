<?php include_once('includes/header.php'); ?>
<main>
	<div id='view-content'>
		<?php
		if (isset($_GET['id']) && (int)$_GET['id'] > 0) {
			$id_image = (int)$_GET['id'];

			try {
				$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (PDOException $e) {
				echo '<span class="error">'. $e->getMessage() .'</span>';
				die();
			}

			try {
				$query = $pdo->prepare('SELECT by_user, src, likes, date FROM images WHERE id_image like :id_image');
				$query->execute(array(':id_image' => $id_image));
				$result = $query->fetch();
			} catch (PDOException $e) {
				echo '<span class="error">'. $e->getMessage() .'</span>';
				die();
			}
				if (!$result['src'] || !$result['by_user'] || !$result['likes']) {
					echo 'Cette photo est introuvable';
				} else {
					$src = $result['src'];
					$likes = unserialize($result['likes']);
					echo '<div id="view-picture"><img src="'. $src .'" alt="'. substr($src, 4, -4) .'"><br /><br />';
					$scrlike = (in_array($_SESSION['id'], $likes)) ? 'img/site/like1.png' : 'img/site/like0.png';
		?>			
					<span id="addlike"><img onClick="changelike('<?php echo $id_image ?>','<?php echo substr($scrlike, -5, -4)  ?>')" id="imglike" src=<?php echo '"'. $scrlike .'"'?> alt="like" ><span id="likenb"><?php echo count($likes) ?></span></span><br /><br />
					<textarea id='area-comment' maxlength='512' cols='5' ></textarea><br />
					<button type="button" onclick="addComment('<?php echo $id_image ?>')">Ajouter un commentaire</button><br />
					<div id="comments-block">
						<ul id="comments-ul">
							<?php
							try {
								$query = $pdo->prepare('SELECT comment, username, date_comment FROM comments WHERE id_image like :id_image');
								$query->execute(array(':id_image' => $id_image));
							} catch (PDOException $e){
								echo '<span class="error">'. $e->getMessage() .'</span>';
								die();
							}
							while (($result = $query->fetch())) {
								echo '<li><div class="username">'. $result['username'] .':</div><div class="comment">'. $result['comment'] .'</div></li>';
							}
							?>				
						</ul>
					</div>
	</div>
				<?php
				}

		} else {
			echo 'Cette photo est introuvable';
		}
				?>
	</div>
</main>

<script type='text/javascript'>
	var addlike = document.getElementById('imglike');
	var countlike = document.getElementById('likenb');
	var textarea = document.getElementById('area-comment');
	var comments = document.getElementById('comments-ul');
	var	tmplike = 0;
	var isadd = 0

	function getxhr () {
		var xmlhr = null;

	    if (window.XMLHttpRequest) { 
	        xmlhr = new XMLHttpRequest();
	    } else if (window.ActiveXObject) {
	        xmlhr = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    return (xmlhr);
	}

	function changelike (idimage, p_islike) {
		var xhr = getxhr();
		tmplike = (isadd) ? !tmplike : !!parseInt(p_islike);
		isadd = 1;
		if (!xhr) {
			return;
		}

		xhr.onreadystatechange = function() {
      		if (xhr.readyState == 4 && xhr.status == 200) {
        		changeLikeComplete(xhr);
      		} 
    	};
		if (!tmplike) {
			xhr.open('GET', 'http://localhost:8080/camagru/like.php?img='+ idimage +'&like=0');
		} else {
			xhr.open('GET', 'http://localhost:8080/camagru/like.php?img='+ idimage +'&like=1');
		}
		xhr.send(null);
	}

	function changeLikeComplete(xhr) {
		var docXML= xhr.responseXML;
	    var errorxml = docXML.getElementsByTagName('error');
	    var successxml = docXML.getElementsByTagName('success');
	    var count = 0;

	    if (errorxml.length) {
	    	console.log(errorxml);
	    }
	    if (successxml.length) {
	    	addlike.src = (!tmplike) ? 'img/site/like1.png' : 'img/site/like0.png';
	    	countlike.textContent = successxml.item(0).firstChild.data;
	    }
	}

	function addComment(idimage) {
		if (textarea.value.length <= 0 || textarea.value.length >= 512) {
			return;
		}
		var message = textarea.value;
		var xhr = getxhr();

		xhr.onreadystatechange = function() {
      		if (xhr.readyState == 4 && xhr.status == 200) {
        		addCommentComplete(xhr);
      		}
    	};
    	xhr.open('POST', 'http://localhost:8080/camagru/comment.php');
    	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    	xhr.send('img='+ idimage + '&comment=' + message);
	}

	function addCommentComplete() {
		console.log('good');
	}

</script>

<?php include_once('includes/footer.php');?>