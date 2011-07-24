<div>
    <?php if(!$fb_data['me']): ?>
        Please login with your FB account: <a href="<?php echo $fb_data['loginUrl']; ?>">login</a>
        <div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=221242157917839&amp;xfbml=1"></script><fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>
    <?php else: ?>
        <img src="https://graph.facebook.com/<?php echo $fb_data['uid']; ?>/picture" />
        <p>Hi <?php echo $fb_data['me']['name']; ?>,<br />
        <a href="<?php echo $fb_data['logoutUrl']; ?>">logout</a> </p>
    <?php endif; ?>
</div>