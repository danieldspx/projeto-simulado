<?php
	require_once '../add/HeaderSession.php';
	//Incluindo a conexão com banco de dados
        include '../private_html_protected/config.php';
        include '../private_html_protected/connection.php';
        include '../private_html_protected/database.php';
        $senha = md5($_POST['nSenhaAtual']);
	if(isset($senha)){
		$senhaDb = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"senha");
        $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"celular");
                if($senha == $senhaDb[0]["senha"]){
                    $nNome = $_POST['nNome'];
                    $nSobrenome = $_POST['nSobrenome'];
                    $nPerfil = $_POST['iconePerfil'];
                    $nSenha = $_POST['nSenha'];
                    $nSenhaConfirm = $_POST['nSenhaConfirm'];
                    $nCelular = $_POST['nCelular'];
                    $comandos[]=null;
                    $contador=0;
                    if(isset($nNome) && $nNome != $_SESSION['usuario']['nome']){
                        $comandos[$contador]= $comandos[$contador]."nome = \"".$nNome."\"";
                        $contador++;
                    }
                    if(isset($nSobrenome) && $nSobrenome != $_SESSION['usuario']['sobrenome']){
                        $comandos[$contador] = $comandos[$contador]."sobrenome = \"".$nSobrenome."\"";
                        $contador++;
                    }
                    if(isset($nPerfil) && $nPerfil != "null"){
                        $comandos[$contador] = $comandos[$contador]."foto_perfil = \"img/".$nPerfil."\"";
                        $contador++;
                    }
                    if(isset($nCelular) && $nCelular != $resposta[0]['celular']){
                        $comandos[$contador] = $comandos[$contador]."celular = \"".$nCelular."\"";
                        $contador++;
                    }
                    if(isset($nSenha) && isset($nSenhaConfirm) && $nSenhaConfirm == $nSenha && $nSenha != null){
                        $comandos[$contador] = $comandos[$contador]."senha = ".$nSenha;
                    }
                    if($contador!=0){
                        $junto = implode(" , ", $comandos);
                        $query = "UPDATE usuarios SET ".$junto." WHERE id = ".$_SESSION['usuario']['id'];
                        DBExecute($query);
                        $_SESSION['Success'] = "Faça Login novamente.";
                        header("Location: ../usuario");
                        
                    } else {
                        $_SESSION['Error'] = "Nenhuma alteração feita.";
                        header("Location: ../usuario");
                    }
                } else {
                    $_SESSION['Error'] = "Nada foi salvo, senha inválida.";
                    header("Location: ../usuario");
                }
	}
        else {
                    $_SESSION['Error'] = "Nada foi salvo, campo de senha vazio.";
                    header("Location: ../usuario");
        }
?>