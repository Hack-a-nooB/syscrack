<?php

use Framework\Application\Container;
use Framework\Application\Render;
use Framework\Application\Settings;
use Framework\Syscrack\Game\Utilities\PageHelper;
use Framework\Syscrack\User;

$session = Container::getObject('session');

if ($session->isLoggedIn()) {

    $session->updateLastAction();
}

if (isset($user) == false) {

    $user = new User();
}

if (isset($pagehelper) == false) {

    $pagehelper = new PageHelper();
}
?>
<html>

<?php

Render::view('syscrack/templates/template.header', array('pagetitle' => 'Syscrack | Admin'));
?>
<body>
<div class="container">

    <?php

    Render::view('syscrack/templates/template.navigation');
    ?>
    <div class="row">
        <div class="col-sm-12">
            <?php

            if (isset($_GET['error']))
                Render::view('syscrack/templates/template.alert', array('message' => $_GET['error']));
            elseif (isset($_GET['success']))
                Render::view('syscrack/templates/template.alert', array('message' => Settings::getSetting('alert_success_message'), 'alert_type' => 'alert-success'));
            ?>
        </div>
    </div>
    <div class="row">

        <?php

        Render::view('syscrack/templates/template.admin.options');
        ?>
        <div class="col-sm-8">
            <h5 style="color: #ababab" class="text-uppercase">
                Riddles Creator
            </h5>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                Using the riddle creator, it is easy to create a set of mysterious questions for
                                players to answer. When creating a new computer, make sure the schema checkbox is
                                ticked,
                                and then set the riddle id to the new riddle you have just created. You can get the
                                riddle id
                                by looking at the header of the panel on the <a href="/admin/riddles/">riddles page.</a>
                            </p>
                            <p>
                                Setting where the player will be sent to when the riddle is answered correctly is set
                                when
                                creating a new computer. Simply tick schema and fill in the appropriate information, and
                                then
                                tick riddle. Fill in the a riddle id ( which will be the question that is asked ) and
                                then fill in
                                a computerid for the user to be sent to if the answer given is correct. Remember to also
                                make sure
                                the computer has a riddle software type.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    Create riddle
                                </div>
                                <div class="panel-body">
                                    <form method="post">
                                        <div class="input-group input-group" style="margin-top: 2.5%;">
                                            <span class="input-group-addon" id="basic-addon1"><span
                                                        class="glyphicon glyphicon-question-sign"></span></span>
                                            <input type="text" class="form-control" name="question"
                                                   placeholder="What is 2+2?" aria-describedby="basic-addon1">
                                            <span class="input-group-addon" id="basic-addon1"><span
                                                        class="glyphicon glyphicon-thumbs-up"></span></span>
                                            <input type="text" class="form-control" name="answer" placeholder="4"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                        <button style="width: 100%; margin-top: 2.5%;" class="btn btn-info" name="action"
                                                value="transfer" type="submit">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Create
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php

    Render::view('syscrack/templates/template.footer', array('breadcrumb' => true));
    ?>
</div>
</body>
</html>
