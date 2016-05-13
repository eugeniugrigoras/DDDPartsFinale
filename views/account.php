<?php require_once 'primo.php'; ?>
<title>Account</title>
<style>
	div.main-content {
        margin-top: 24px;
        margin-bottom: 24px!important;
    }

    div.main-content form {

    }

    div.title {
        background-color: #444;
        color:white;
        padding:24px;
        font-weight: 300;
        text-transform: uppercase;
        text-align: left;
    }

    div.row {
        margin-bottom: 0;
    }
    #avatar img {
        width: 100%;
        height: 100%;
    }
    #avatar {
        overflow: hidden;
        border-radius: 50%;
        
        width: 150px;
        height: 150px;
        background-size: cover; 
        z-index: 2;
        text-align: center;
        margin:auto auto;
    }

    div.account {
        padding: 0px;
    }

    .number {
        @apply(--paper-font-title);
        color: #444;
        margin-bottom: 5px;
    }

    .subtitle {
        @apply(--paper-font-caption);
        color: #686868;
        font-size: 10px;
    }
    .card:hover {
        background-color: #ddd;
    }

    .card {
        padding:12px 24px!important;
        text-align: center;
        position: relative;
        display: inline-block;
        background-color: #fff;
        box-shadow: 0 0 0 #fff;
        margin:0;
        moz-transition: background-color 0.25s;
		transition: background-color 0.25s;
		webkit-transition : background-color 0.25s;
    }

    .card +.card {
        border-left:1px solid #ddd;
    }

    @media only screen and (min-width : 993px) {
    	div.card-reveal {
    		border-bottom:1px solid #ddd!important;
    	}
    }

    @media only screen and (max-width : 992px) {
        div.card:nth-of-type(1) {
            border-right:1px solid #ddd;
        }
        div.card:nth-of-type(3) {
            border-right:1px solid #ddd;
        }
        div.card:nth-of-type(5) {
            border-right:1px solid #ddd;
        }
        .card +.card {
            border-left:0px solid #ddd;
        }
        .card {
            border-bottom:1px solid #ddd;
        }
        #followed-collection {
            border-bottom:0px solid #ddd;
        }
        #my-collection {
            border-bottom:0px solid #ddd;
        }
    }

    .user-box + .user-box {

    }

    .following-button:hover {
        cursor:pointer;
    }
</style>
<?php require_once 'secondo.php'; ?>
<main>
    <div class="container main-content row z-depth-1">
    	<div class="card col s12" style="padding:0!important">
	        <div class="title truncate"><i style="margin:0!important; cursor:pointer" class="activator material-icons right noselect">info_outline</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?></div>
	        <div class="" style="padding:24px; background-image:url('/img/bg2.jpg'); background-size:cover">
	            <div id="avatar" class="z-depth-1">
	                <img src="<?php echo requestPath()."/profile.jpg";?>" alt="" class="circle">
	            </div>
	        </div>
	        <div class="card-reveal" style=" text-align:left; color:#444; width:inherit!important">
	        	<span class="card-title"><i class="material-icons right noselect">close</i><?php echo $_SESSION["NOME"]." ".$_SESSION["COGNOME"]; ?> - Information</span>
	        	<p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">email</i><?php echo requestData()["EMAIL"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">place</i><?php echo requestData()["COMUNE"];?></p>
	        	<p class="valign-wrapper"><i class="valign material-icons noselect" style="margin-right:20px;">description</i><?php echo requestData()["DESCRIZIONE"];?></p>    	
	        </div>
        </div>
        <div class="sections col s12" style="margin-bottom:0; padding:0!important">
            <div class="card col l2 m6 s12 waves-effect" id="following">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate" onclick="following()">FOLLOWING</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="follower">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_utenti where FK_UTENTE_SEGUITO=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWERS</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="likes">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_like_progetti as p, utenti_like_collezioni as c where p.FK_UTENTE=".$_SESSION["ID"]." and c.FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">LIKES</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="projects">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM progetti where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">PROJECTS</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="my-collections">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM collezioni where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">MY COLLECTIONS</div>
            </div>
            <div class="card col l2 m6 s12 waves-effect" id="followed-collections">
                <div class="number">
                    <?php
                        $QUERY=executeQuery("select * FROM utenti_seguono_collezioni where FK_UTENTE=".$_SESSION["ID"]);
                        echo $QUERY->num_rows; 
                    ?>
                </div>
                <div class="subtitle truncate">FOLLOWED COLLECTIONS</div>
            </div>
        </div>
    </div>

    <!-- ------------------------------------------------------------------------------------------------------------ -->

    <div class="row users container main-content">
        <div class="col s12 m6 l4 z-depth-1 user-box">
            <p style="margin:0!important; color:#424242" class="valign-wrapper following-button" onclick="follow()"><i id="follow-icon" class="valign material-icons noselect" style="margin-bottom:-48px!important;">radio_button_unchecked</i></p>
            <div class="user row" style="background-color:white; padding:12px">
                <div class="user-header">
                    <div id="avatar" class="z-depth-1">
                        <img src="<?php echo requestPath()."/profile.jpg";?>" alt="" class="circle">
                    </div>
                    <p class="center-align truncate" style="margin:0; font-weight:600; color:#424242; margin-top:12px">Eugenio Grigoras</p>
                    <p class="center-align" style="margin:0!important; font-size:14px"><a class="btn-flat disabled truncate" style="text-transform:capitalize; color:#757575"><i class="material-icons left noselect">place</i><?php echo substr(strrchr(requestData()["COMUNE"], "-"),2);?></a></p>
                </div>
                
            </div>
            <div class="user-card row" style="border-top:1px solid #ddd;">
                <div class="col s6 center-align card"> 
                    <div class="number" style="font-weight:600; color:#424242;">
                        24
                    </div>
                    <div class="subtitle truncate" style="color:#757575">FOLLOWING</div>
                </div>
                <div class="col s6 center-align card">
                    <div class="number" style="font-weight:600; color:#424242;">
                        24
                    </div>
                    <div class="subtitle truncate" style="color:#757575">FOLLOWERS</div>
                </div>
            </div>
        </div>      
    </div>

</main>

<?php require_once 'terzo.php'; ?>

<script>
    function follow () {
        if (_('follow-icon').innerHTML == 'radio_button_checked') {
            _('follow-icon').innerHTML = 'radio_button_unchecked';
        } else {
            _('follow-icon').innerHTML = 'radio_button_checked';
        }
    }
    function _(el){
        return document.getElementById(el);
    }
    function following() {
        // SELECT * FROM `utenti` WHERE utenti.ID in (SELECT FK_UTENTE_SEGUITO FROM `utenti_seguono_utenti` WHERE FK_UTENTE=42);
    }

    function follower() {
        // SELECT * FROM `utenti` WHERE utenti.ID in (SELECT FK_UTENTE FROM `utenti_seguono_utenti` WHERE FK_UTENTE_SEGUITO=42);
    }
</script>

<?php require_once 'quarto.php'; ?>