<?php 

class Registro{
	function __construct(){
	 include("../inc/config.php");
	 include("../inc/functions.php");
	 include("../inc/mysql.php");
	}
	function verificar($usuario, $pass){
		$cnx = new MySQL();
		$res = "select usuario from registro where usuario='$usuario'";
		$resulta=$cnx->query($res);
		$resulta->read();
			if($resulta->count()>0){
					$resp = "select clave,nombres,id from registro where clave ='$pass'";
					$respuesta = $cnx->query($resp);
					$respuesta->read();
					$respuesta->next();
					if($respuesta->count()>0){
						$datos = array(
										"rpta"=>"ok",
										"nombres" => $respuesta->field("nombres"),
										"id_user" => $respuesta->field("id")
										);
						return $datos;				
					}else{
						$mensaje = array("rpta"=>"error","mensaje"=>"la clave no es la correcta");
						return $mensaje;
					}
			}else{
				$mensaje = array("rpta"=>"error","mensaje"=>"El usuario no existe");
				return $mensaje;
			}
	}
	
	function crear_usuario($email,$usuario,$password,$phone,$picture){
		$cnx = new MySQL();
		///verificamos existencia de registros
		$query = "select * from registro where  email='$email'";
		$res  = $cnx->query($query);
		$res->read();
		
		$query2 = "select * from registro where  usuario='$usuario'";
		$res2  = $cnx->query($query);
		$res2->read();
		
		$query3 = "select * from registro where  phone='$phone'";
		$res3  = $cnx->query($query);
		$res3->read();
		
		if($res->count()==0){
				if($res2->count==0){
						if($res3->count()==0){
							///////procede registro	
							$aemail     = getParam($email,"");
							$ausuario   = getParam($usuario,"");
							$apassword  = md5(getParam($password,""));
							$aphone     = getParam($phone,"");
							$picture    = getParam($picture,"");
							$insertar = sprintf("insert into registro(email,usuario,password,phone,picture)values(%s,%s,%s,%s,%s)",
										getSQL($aemail, "text"),
										getSQL($ausuario, "text"),
										getSQL($apassword, "text"),
										getSQL($phone, "text"),
										getSQL($apicture, "text")
										);
							$cnx->execute($insertar);
							$mensaje = array("rpta"=>"ok","mensaje"=>"el registro es exitoso");
							return $mensaje;
							
						}else{
							$mensaje = array("rpta"=>"error","mensaje"=>"El número de telefono esta registrado");
						}
					
				}else{
					$mensaje = array("rpta"=>"error","mensaje"=>"el nombre de usuario existe");
					return $mensaje;
				}
			
		}else{
			 $mensaje = array("rpta"=>"error","mensaje"=>"El correo esta registrado");
			return $mensaje;
		}
	}
	function actualizar($email,$usuario,$password,$phone,$picture,$user_id){
		
		 					 $aemail     = getParam($email,"");
							$ausuario   = getParam($usuario,"");
							$apassword  = md5(getParam($password,""));
							$aphone     = getParam($phone,"");
							$picture    = getParam($picture,"");
							$actualizar = sprintf("update  registro set email='%s',usuario='%s',password='%s',phone='%s',picture='%s' where id = '$id_user')",
										getSQL($aemail, "text"),
										getSQL($ausuario, "text"),
										getSQL($apassword, "text"),
										getSQL($phone, "text"),
										getSQL($apicture, "text")
										);
							$cnx->execute($actualizar);
							$mensaje = array("rpta"=>"ok","mensaje"=>"actualización correcta");
							return $mensaje;
	}

   function borrar($id_user){
   		$cnx = new MySQL();
   		$query = "delete from registro where id='$id_user'";
		$result= $cnx->query($query);
		$result->read();
		return array("rpta"=>"ok","mensaje"=>"borrado con exito");
   }	
   
}
