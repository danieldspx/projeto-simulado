<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';
    date_default_timezone_set('America/Sao_Paulo');
    
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
       echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
       exit();
    }

    $texto = $_POST['texto'];
    $nquestao = $_POST['nquestao'];
    $excluir = $_POST['excluir'];
    $idcomentario = $_POST['idcomentario'];
    if($excluir == "nao"){
        $existe_comentario = DBSearch("comentarios","WHERE questoes_idquestao = ".$nquestao." AND usuarios_id = ".$_SESSION['usuario']['id'],"usuarios_id");
        if(empty($existe_comentario[0])){
            $dados['comentario']=$texto;
            $dados['pontuacao']=0;
            $dados['avaliacoes']=null;
            $dados['questoes_idquestao']=$nquestao;
            $dados['usuarios_id'] = $_SESSION['usuario']['id'];
            $dados['mes_postagem'] = date('m');
            
            DBCadastro("comentarios",$dados);
            
            $get_idcmm = DBSearch("comentarios","INNER JOIN usuarios ON usuarios.id = usuarios_id WHERE questoes_idquestao = $nquestao AND usuarios_id = ".$_SESSION['usuario']['id'],"idcomentario,CONCAT(usuarios.nome,' ',usuarios.sobrenome) AS nome");
            
            if(isset($get_idcmm[0]['idcomentario'])){
                echo $get_idcmm[0]['idcomentario']."&&&";
                echo "<div style='margin-left: 0px;' class='row' id='painelComentario".$nquestao.$get_idcmm[0]['idcomentario']."'>";
                    echo "<div class='panel panel-primary'>";
                        echo "<div class=\"panel-heading\"><h3 style=\"padding-left: 0px;\" class=\"panel-title col-md-10\">".$get_idcmm[0]['nome']."</h3><span id=\"pontos".$_SESSION['usuario']['id']."\" class=\"badge\" >0</span>&nbsp;&nbsp;&nbsp;<span id=\"imagem".$nquestao.$get_idcmm[0]['idcomentario']."\"><i class='material-icons' title='Excluir resolução' style='cursor: pointer;vertical-align: middle;' onclick='removeResolucao(".$nquestao.",".$get_idcmm[0]['idcomentario'].")'>delete_forever</i></span></div>";
                        echo "<div class=\"panel-body\"><p style=\"position: relative; display: inline-block;\">".$texto."</p></div>";
                    echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class=\"alert alert-danger\" style='font-weight: 700;' role=\alert\">Você já comentou uma vez.</div>";
        }
    } else{
        $comentarioCurrent = DBSearch("comentarios","WHERE idcomentario = ".$idcomentario,"mes_postagem,pontuacao");
        if(date('m') == $comentarioCurrent[0]['mes_postagem']){ // Se o excluir a postagem no mês em que postou, nesse caso perde-se // os pontos adquiridos nela
            $comando = 'UPDATE usuarios SET pontuacao = (pontuacao-'.$comentarioCurrent[0]['pontuacao'].') WHERE usuarios.id = '.$_SESSION['usuario']['id'].';';
            DBExecute($comando);
        }
        $query = "DELETE FROM comentarios WHERE idcomentario = ".$idcomentario;
        DBExecute($query);
        
        echo "<div id='section_usercmm$nquestao'>";
            echo "<div id=\"buttonsResolution$nquestao\" style=\"margin: 20px 0px;\"><button onclick=\"writeResolution(".$nquestao.")\" style=\"margin-right: 15px;\" id=\"write".$nquestao."\" class=\"btn btn-primary btn-info\">Escrever resolução <span class=\"glyphicon glyphicon-comment\"></span></button>";
            echo "<button onclick=\"showMathML('".$nquestao."')\" style=\"display: none;\" id=\"math".$nquestao."\" class=\"btn btn-primary btn-warning\">Símbolos Matemáticos <i class=\"fa fa-calculator\" aria-hidden=\"true\"></i></button></div>";
            echo "<span id=\"MathField".$nquestao."\"></span>";
            echo "<span id=\"digite_comentario".$nquestao."\" class=\"displaynone\">";
                echo "<textarea type=\"text\" rows='4' style=\"display: block; margin-bottom: 20px;\" id=\"txtResolucao".$nquestao."\" cols='50' maxlength='800' placeholder='Digite sua solução. Máx: 800 caracteres'></textarea>";
                echo "<div id=\"editorContainer\"></div>";
                echo "<input class='btn btn-info btn-sm' type='submit' value='Enviar Resolução' onclick=\"addResolucao(".$nquestao.")\">";
            echo "</span>";
        echo "</div>";

    }
?>