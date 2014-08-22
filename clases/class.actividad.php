<?php 
class Actividad{
	function __construct(){	
	 include("../inc/config.php");
	 include("../inc/functions.php");
	 include("../inc/mysql.php");
	}
    function like($id_user,$id_post){
    	$cnx = new MySQL();
		$query = "insert into like (id_user,id_post)values('$id_user','$id_post')";
		$res = $cnx->query($query);
		$res->read();
		return array("rpta"=>"ok","mensaje"=>"agregado");
    }	
	
	function totalLike($id_post){
		$cnx = new MySQL();
		$query = "select COUNT(id_post) from like where id_post='$id_post'";
		$res = $cnx->query($query);
		$res->read();
		return array("rpta"=>"ok","mensaje"=>"totales");
	}
	
	function comentarios($comentario,$id_user,$id_post){
		$cnx = new MySQL();
		$query = "insert into comentarios (id_user,id_post,comentario)values('$id_user','$id_post','$comentario')";
		$res = $cnx->query($query);
		$res->read();
		return array("rpta"=>"ok","mensaje"=>"agregado comentario");
	}
	function numeroComentario($id_post){
		$cnx = new MySQL();
		$query = "select COUNT(id_post) from comentarios where id_post='$id_comentario'";
		$res = $cnx->query($query);
		$res->read();
		return array("rpta"=>"ok","mensaje"=>"totales");
	}
	
	function comentaristas($id_post){
		$cnx = new MySQL();
		$query = "select registro.picture, registro.usuario, registro.id, comentarios.comentario from comentarios left join registro on comentarios.id_user=registro.id where id_post='$id_post'";
		$res = $cnx->query($query);
		$res->read();
		while($res->next()){
		
		$comentarios[]= array(   "rpta"=>"ok",
						"imagen"      => $res->field("picture"),
						"usuario"     => $res->field("usuario"),
						"id_user"     => $res->field("id"),
						"comentarios" => $res->field("comentarios")
						);
					}
			return $comentarios;
		
	}
}
