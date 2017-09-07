<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录超时</title>
    <link rel="stylesheet" type="text/css" href="/Public/adminTpl/style/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/Public/adminTpl/style/login.css">
    <script type="text/javascript">
    if (window != window.top) {
        window.top.location.replace(window.location)
        // 这是直接代替外窗，你也可以干别的
    }
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row aliyun-login-bg-1"></div>
        <div class="row aliyun-login-bg-2"></div>
        <div class="vertical-center">
            <div class="logo-center">
                <p class="text-center logo">登录超时</p>
            </div>
            <div class="login-center">
                <div class="login">
                    <form class="form-horizontal" method="post">
                      <div class="form-group">
                        <label class="sr-only" for="exampleInputEmail3">邮箱</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail3" value="<?php echo ($adminRow["email"]); ?>" placeholder="用户名" disabled>
                      </div>
                      <div class="form-group">
                        <label class="sr-only" for="exampleInputPassword3">密码</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword3" placeholder="密码">
                      </div>
                      <button type="submit" class="btn btn-primary btn-sm btn-block">登录</button>
                      <div class="form-group">
                          <p class="text-center"><a href="#">忘记密码</a></p>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>