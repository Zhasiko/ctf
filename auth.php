
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>
    <div class="container">
        <div class="photo-container">
            <p class="center"><a href="/"><img style="max-width:100%;" src="/library/img/logo.png" alt="Logo" /></a></p>
        </div>
        <div class="form-container">
            
            
            <div class="login-form">
                <h1>LOG IN</h1>
                <div class="form-group form-floating-label">
                    <input id="login" onKeyDown="func_login(event);" type="text" class="form-control input-border-bottom" required value="<?=@$_GET["p"]?>" />
                    <label for="login" class="placeholder">Логин</label>
                </div>
                <div class="form-group form-floating-label">
                    <input id="pass" onKeyDown="func_login(event);" type="password" class="form-control input-border-bottom" required>
                    <label for="pass" class="placeholder">Пароль</label>
                    <div class="show-password">
                        <i class="icon-eye"></i>
                    </div>
                </div>
                <div class="row form-sub m-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember" checked="checked" />
                        <label class="custom-control-label" for="remember">Запомнить меня</label>
                        <a href="/forget.php" class="float-right">Забыли пароль?</a>
                    </div>
                    
                </div>
                <div class="form-action mb-3">            
                    <a onClick="login()" class="btn btn-login">Войти</a>
                    <div id="protocolLog"></div>
                </div>            
            </div>
        </div>
    </div>
</body>
</html>
