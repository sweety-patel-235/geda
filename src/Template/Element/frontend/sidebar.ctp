<div class="col-md-3">
    <aside class="sidebar">

        <hr class="invisible mt-xl mb-sm">

        <h4 class="heading-primary green-color">Contact <strong>Us</strong></h4>
        <p>Fill out the form below for registering with AHA Rooftop Solar Helper and we will get back to you as soon as possible</p>

        <form id="contactForm" action="php/contact-form.php" method="POST" novalidate="novalidate">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Full Name *</label>
                        <input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name" required="" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Company Name *</label>
                        <input type="text" value="" data-msg-required="Please enter your company name." maxlength="100" class="form-control" name="name" id="c-name" required="" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Phone No. *</label>
                        <input type="number" value="" data-msg-required="Please enter your phone number." maxlength="100" class="form-control" name="name" id="p-no" required="" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Email address *</label>
                        <input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email" required="" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Your Address *</label>
                        <input type="text" value="" data-msg-required="Please enter proper adreess." maxlength="100" class="form-control" name="name" id="name" required="" aria-required="true">
                        <input type="text" value="" data-msg-required="Please enter proper adreess." maxlength="100" class="form-control" name="name" id="name" required="" aria-required="true">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>City</label>
                        <select data-msg-required="Please enter the subject." class="form-control" name="subject" id="subject" required="" aria-required="true">
                            <option value="">...</option>
                            <option value="Option 1">Option 1</option>
                            <option value="Option 2">Option 2</option>
                            <option value="Option 3">Option 3</option>
                            <option value="Option 4">Option 4</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Your Coverage Area (States)</label>
                        <select data-msg-required="Please enter the subject." class="form-control" name="subject" id="subject" required="" aria-required="true">
                            <option value="">...</option>
                            <option value="Option 1">Option 1</option>
                            <option value="Option 2">Option 2</option>
                            <option value="Option 3">Option 3</option>
                            <option value="Option 4">Option 4</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Presences (in Cities)</label>
                        <select data-msg-required="Please enter the subject." class="form-control" name="subject" id="subject" required="" aria-required="true">
                            <option value="">...</option>
                            <option value="Option 1">Option 1</option>
                            <option value="Option 2">Option 2</option>
                            <option value="Option 3">Option 3</option>
                            <option value="Option 4">Option 4</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <input type="submit" value="GET in Touch" class="btn btn-primary mb-xl" data-loading-text="Loading...">
                </div>
                <div class="col-md-8">
                    <div class="alert alert-success hidden" id="contactSuccess">
                        Message has been sent to us.
                    </div>

                    <div class="alert alert-danger hidden" id="contactError">
                        Error sending your message.
                    </div>
                </div>
            </div>
        </form>

        
    </aside>
</div>