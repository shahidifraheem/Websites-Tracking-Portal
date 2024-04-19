<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_theme('header', $data); ?>

<?= check_error(); ?>
<form action="<?= current_url() ?>" method="post" class="form-boxed">
    <h1 class="title">Update Password</h1>
    <div class="relative mb-4">
        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Enter password...">
    </div>
    <div class="relative mb-4">
        <label for="cpassword">Password</label>
        <input type="text" id="cpassword" name="cpassword" placeholder="Confirm password...">
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
    <button type="submit">Update</button>
</form>
<?php $this->include_theme('footer', $data); ?>