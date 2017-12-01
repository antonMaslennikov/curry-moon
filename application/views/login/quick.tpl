<div id="gkPopupLogin">	
    <div class="gkPopupWrap">
        <div id="loginForm">
            <h3>Войдите <small>или <a href="/ru/users/registration">зарегистрируйтесь</a></small></h3>

            <div class="clear overflow">

                <form action="/ru/users/login" method="post" id="login-form" >
                    <fieldset class="userdata">
                    <p id="form-login-username">
                    <label for="modlgn-username">Логин</label>
                    <input id="modlgn-username" type="text" name="login" class="inputbox" required="required"  size="24" />
                    </p>
                    <p id="form-login-password">
                    <label for="modlgn-passwd">Пароль</label>
                    <input id="modlgn-passwd" type="password" name="password" class="inputbox" required="required" size="24"  />
                    </p>
                    <div id="form-login-remember">
                    <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
                    <label for="modlgn-remember">Запомнить меня</label>
                    </div>
                    <div id="form-login-buttons">
                    <input type="submit" name="Submit" class="button" value="Войти" />
                    </div>
                    <input type="hidden" name="option" value="com_users" />
                    <input type="hidden" name="task" value="user.login" />
                    <input type="hidden" name="return" value="aHR0cHM6Ly9jdXJyeS1tb29uLmNvbS9ydQ==" />
                    <input type="hidden" name="a896e2d7f6dd67a5535de814dd934423" value="1" />				
                    </fieldset>
                    <ul>
                    <li> <a href="/ru/users/forgot-password"> Забыли пароль?</a> </li>
                    <li> <a href="/ru/users/forgot-username"> Забыли логин?</a> </li>
                    </ul>
                    <div class="posttext">  </div>
                </form>
            </div>
        </div>	     
    </div>
</div>