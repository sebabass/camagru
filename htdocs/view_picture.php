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
					echo '<span class="error">Cette photo est introuvable</span>';
				} else {
					$src = $result['src'];
					$likes = unserialize($result['likes']);

					echo '<div id="view-picture"><img src="'. $src .'" alt="'. substr($src, 4, -4) .'"></div><br />';
					if ($result['by_user'] == $_SESSION['id']) {
						echo '<span>par '. $_SESSION['username'] .'</span> (<a href="delete.php?id='. $id_image .'">supprimer la photo</a>)<br />';
					}
					$scrlike = (in_array($_SESSION['id'], $likes)) ? 'img/site/like1.png' : 'img/site/like0.png';
		?>			
					<span id="addlike"><img onClick="changelike('<?php echo $id_image ?>','<?php echo substr($scrlike, -5, -4)  ?>')" id="imglike" src=<?php echo '"'. $scrlike .'"'?> alt="like" ><span id="likenb"><?php echo count($likes) ?></span></span><br /><br />
					<span id='error-view' class="error"></span><br />
					<textarea id='area-comment' maxlength='512' cols='5' ></textarea><br />
					<button type="button" onclick="addComment('<?php echo $id_image ?>')">Ajouter un commentaire</button><br />
					<div id="comments-block">
						<ul id="comments-ul">
							<?php
							try {
								$query = $pdo->prepare('SELECT comment, username FROM comments WHERE id_image like :id_image');
								$query->execute(array(':id_image' => $id_image));
							} catch (PDOException $e){
								echo '<span class="error">'. $e->getMessage() .'</span>';
								die();
							}
							while (($result = $query->fetch())) {
								echo '<li><span class="username">'. $result['username'] .':</span><span class="comment">'. $result['comment'] .'</span></li>';
							}
							?>				
						</ul>
					</div>
	</div>
				<?php
				}

		} else {
			echo '<span class="error">Cette photo est introuvable</span>';
		}
				?>
	</div>
</main>

<script type='text/javascript'>
	var addlike = document.getElementById('imglike');
	var countlike = document.getElementById('likenb');
	var textarea = document.getElementById('area-comment');
	var comments = document.getElementById('comments-ul');
	var errorview = document.getElementById('error-view');
	var	tmplike = 0;
	var isadd = 0;

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
		clear();
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
	    	errorview.textContent = errorxml.item(0).firstChild.data;
	    	return;
	    }
	    if (successxml.length) {
	    	addlike.src = (!tmplike) ? 'img/site/like1.png' : 'img/site/like0.png';
	    	countlike.textContent = successxml.item(0).firstChild.data;
	    }
	}

	function addComment(idimage) {
		clear();
		if (textarea.value.length <= 0 || textarea.value.length > 512) {
			return;
		}
		var message = textarea.value;
		var xhr = getxhr();

		xhr.onreadystatechange = function() {
      		if (xhr.readyState == 4 && xhr.status == 200) {
        		addCommentComplete(xhr, message);
      		}
    	};
    	xhr.open('POST', 'http://localhost:8080/camagru/comment.php');
    	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    	xhr.send('img='+ idimage + '&comment=' + message);
	}

	function addCommentComplete(xhr, message) {
		var docXML= xhr.responseXML;
		var errorxml = docXML.getElementsByTagName('error');
	    var successxml = docXML.getElementsByTagName('success');

		if (errorxml.length) {
	    	errorview.textContent = errorxml.item(0).firstChild.data;
	    	return;
	    }
	    if (successxml.length) {
	    	comments.innerHTML += '<li><span class="username">'+ successxml.item(0).firstChild.data +':</span><span class="comment">'+ message +'</span></li>';
	    }
	}

	function clear() {
		errorview.textContent = '';
	}

</script>

<?php include_once('includes/footer.php');?>