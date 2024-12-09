<?php 
$Customers      = $this->Session->read('Customers');
$Members        = $this->Session->read('Members');
$USER           = '';
$MEMBER         = '';
$WelcomeText    = "";
if(isset($Customers['id'])) {
    $USER           = $this->Session->read('Customers.id');
    $CName          = $this->Session->read('Customers.name');
    $CEmail         = $this->Session->read('Customers.email');
    if (!empty($CName)) {
        $WelcomeText    = "Welcome, ".$CName;
    } else {
        $WelcomeText    = "Welcome, ".$CEmail;
    }
} else if(isset($Members['id'])) {
    $MEMBER         = $this->Session->read('Members.id');
    $CEmail         = $this->Session->read('Members.email');
    $WelcomeText    = "Welcome, ".$CEmail;
    $member_type 	= $this->Session->read('Members.member_type');
    $WelcomeText    .= " (";
    switch ($member_type) {
        case '6001':
        if($this->Session->read('Members.state')==4)
        {
            $WelcomeText .= " GEDA ";
        }
        else
        {
            $WelcomeText .= " JREDA ";
        }
            break;
        case '6002':
            $WelcomeText .= " DISCOM ";
            break;
        case '6002':
            $WelcomeText .= " CEI ";
            break;
    }
    if ($member_type == 6002) {
        $area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
        if (!empty($section)) {
            $WelcomeText .= " / Section ";
        } else if (!empty($subdivision)) {
            $WelcomeText .= " / Subdivision ";
        } else if (!empty($division)) {
            $WelcomeText .= " / Division ";
        } else if (!empty($circle)) {
            $WelcomeText .= " / Circle ";
        } else if (!empty($circle)) {
            $WelcomeText .= " / Area ";
        }
    }
    $WelcomeText    .= ")";
}
echo "<font color='".COLOR_ORANGE."'>".$WelcomeText."</font>";
?>