<?php
	define($SEGUNDOS_DIA,86400);
	require_once '../add/HeaderSession.php';
	//Incluindo a conexão com banco de dados
        require '_protected/configValida.php';
        require '_protected/conexaoValida.php';
        require '_protected/databaseValida.php';
	//O campo usuário e senha preenchido entra no if para validar
        $email = DBEscape($_POST['nEmail'],true); //Escapar de caracteres especiais, como aspas, prevenindo SQL injection
	if(strpos($email, "@") === false){
		$email = $email."@ufv.br";
	}
        $senha = md5(DBEscape($_POST['nPassword']));
	if((isset($email)) && (isset($senha))){
		//Buscar na tabela usuario o usuário que corresponde com os dados digitado no formulário
		$resposta = DBSearch("usuarios","WHERE email = \"".$email."\" AND senha = \"".$senha."\" LIMIT 1","senha, email, nome, niveis_acesso_id,id,foto_perfil,sobrenome,id_sessao,created,plano");
		//Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
		if($resposta[0]['niveis_acesso_id'] == '4' || ($resposta[0]['niveis_acesso_id'] == '3' && $resposta[0]['plano'] == '2')){
			date_default_timezone_set('America/Sao_Paulo');
			$dateCreated = new DateTime($resposta[0]['created']);
			$dateNow = new DateTime(date('d'.'-'.'m'.'-'.'Y'.' '.'H'.':'.'i'.':'.'s'));
			$dateDiff = $dateCreated->diff($dateNow);
			$dateFormatedDiff = $dateDiff->format('%d'.'-'.'%m'.'-'.'%y'.'&&'.'%s'.':'.'%i'.':'.'%s');
			$tempoAtivo = 0;
			$explode = explode("&&",$dateFormatedDiff);
			$DMY = explode('-',$explode[0]); //Dia Mês e Ano
			if($resposta[0]['niveis_acesso_id'] == '4'){
				if($DMY[1] != 0 || $DMY[2] != 0){
					$_SESSION['loginErro'] = "Seu tempo de avaliação chegou ao fim...";
					header("Location: ../principal");
					exit();
				} else {
					$tempoAtivo = $DMY[0]*86400;
					$HMS = explode(':',$explode[1]);//Hora Minuto Segundo
					$tempoAtivo += $HMS[0]*3600 + $HMS[1]*60 + $HMS[2];
					if($tempoAtivo >= $SEGUNDOS_DIA*3){
						$_SESSION['loginErro'] = "Seu tempo de avaliação chegou ao fim...";
						header("Location: ../principal");
						exit();
					}
				}
			} else {
				if($DMY[1] != 0 || $DMY[2] != 0){
					$_SESSION['loginErro'] = "Seu tempo de assinatura chegou ao fim...";
					header("Location: ../principal");
					exit();
				}
			}
		}

		if(isset($resposta) && $email== $resposta[0]["email"] && $senha==$resposta[0]["senha"]){
            unset($resposta[0]["senha"]);//Exclui senha
			$idsessao = mt_rand();
			if($idsessao==$resposta[0]["id_sessao"]){
				$idsessao++;
			}
			$_SESSION['usuario']['nome'] = $resposta[0]["nome"];
			$_SESSION['usuario']['nivelAcessoId'] = $resposta[0]["niveis_acesso_id"];
			$_SESSION['usuario']['email'] = $resposta[0]["email"];
            $_SESSION['usuario']['sobrenome'] = $resposta[0]["sobrenome"];
            $_SESSION['usuario']['id'] = $resposta[0]["id"];
            $_SESSION['usuario']['foto'] = $resposta[0]["foto_perfil"];
			date_default_timezone_set('America/Sao_Paulo');
			$acesso = date('d'.'-'.'m'.'-'.'Y'.' '.'H'.':'.'i'.':'.'s');
			$query_sessao = "UPDATE usuarios SET id_sessao = $idsessao, acesso = \"$acesso\"  WHERE id = ".$_SESSION['usuario']['id'].";";
			DBExecute($query_sessao);
			$_SESSION['usuario']['sessao'] = $idsessao;
			if($_SESSION['usuario']['nivelAcessoId'] == "1"){
				header("Location: ../administrador");
			}elseif($_SESSION['usuario']['nivelAcessoId'] == "2"){
				header("Location: ../colaborador");
			}else{
				header("Location: ../questoes");
			}
		//Não foi encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
		//redireciona o usuario para a página de login
		}else{
                    unset($resposta);
                    unset($email);
                    unset($senha);
			//Váriavel global recebendo a mensagem de erro
			$_SESSION['loginErro'] = "Usuário ou senha inválido";
            header("Location: ../principal");
		}
	//O campo usuário e senha não preenchido entra no else e redireciona o usuário para a página de login
	}else{
            $_SESSION['loginErro'] = "Usuário ou senha inválido";
			header("Location: ../principal");
	}
?>
