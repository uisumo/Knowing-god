<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true" style="z-index:999999;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php
            $from = 'lms';
            if ( ! empty( $from ) ) {
                $from = 'wp';
            }
            ?>
            @include('lms-forntview.login-form', array('from' => $from ))
        </div>
    </div>
</div>
<!-- /Modal -->
