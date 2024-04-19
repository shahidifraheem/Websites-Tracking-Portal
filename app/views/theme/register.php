<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_theme('header', $data); ?>

<div class="wrapper padding-y">
    <h1 class="title">Register</h1>
    <form action="<?= current_url() ?>" method="post" class="form-boxed">
        <div class="input-box">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter Name...">
        </div>
        <div class="input-box">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email..." required>
        </div>
        <div class="input-box">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter Password..." required>
        </div>
        <div class="input-box">
            <label for="password2">Confirm Password</label>
            <input type="password" id="password2" name="password2" placeholder="Confirm Password..." required>
        </div>
        <div class="input-box re-captcha-box">
            <?php
            // Generate a new mathematical problem
            $problemData = generateMathProblem();
            $mathProblem = $problemData['problem'];
            $answer = $problemData['answer'];
            $hashedAnswer = password_hash($answer, PASSWORD_DEFAULT);
            ?>
            <label for="re-captcha">Solve re-captcha</label>
            <div class="re-captcha">
                <label for="captcha"><?php echo $mathProblem; ?> = </label>
                <input type="text" id="captcha" name="captcha" required>
                <input type="hidden" name="hash" value="<?= $hashedAnswer; ?>">
            </div>
        </div>
        <button type="submit" class="form-btn">Create account</button>
    </form>
</div>

<?php $this->include_theme('footer', $data); ?>