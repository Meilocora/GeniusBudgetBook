<section class="login-container">
    <h1>Login</h1>
    <form action="./?route=login/verify" method="POST" id="login">
        <div class="form-row">
            <label for="username" class="label">Username:</label>
            <input type="text" name="username" id="username" class="input" placeholder="Please insert your username..." required><br>
        </div>
        <div class="form-row">
            <label for="password" class="label">Password:</label>
            <input type="password" name="password" id="password" class="input" required>
        </div>
        <div class="form-row">
            <input type="submit" value="Login" class="btn">
            <a href="./" class="cancel-btn">Cancel</a>
        </div>
    </form>
</section>