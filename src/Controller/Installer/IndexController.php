<?php
namespace App\Controller\Installer;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Event\Event;


class IndexController extends InstallerAppController
{

	public function initialize()
    {
        parent::initialize();

		$this->loadModel('Customers');
        $this->loadModel('Events');
        $this->loadModel('Vendors');
        $this->loadModel('EventInvitations');
    }

    public function index()
    {
        //echo "vendor home page here";
    }

    public function dashboard()
    {
        //echo "vendor dashboard here";
    } 


    public function profile()
    {
        $customerId = $this->Auth->user('id');
        $user = $this->Customers->find('all')
                ->where(['Customers.id' => $customerId])
                ->contain('Vendors')->first();
        $userEntity = $this->Customers->patchEntity($user, $this->request->data,['validate' => 'vendor']);
        
        if (!$userEntity->errors() && !empty($this->request->data)) {
            $this->Customers->patchEntity($user, $this->request->data);

            if ($this->Customers->save($user)) {
                $this->Flash->success(__('Your profile has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update your profile.'));
        }

        $this->set('user', $user);
    }


    public function register($eventInvitationId, $activationCode)
    {
        $eventInvitationId = decode($eventInvitationId);
        
        $eventInvitationData = $this->EventInvitations->findByIdAndActivationCode($eventInvitationId, $activationCode)->first();
        if(empty($eventInvitationData)){
           exit("Invalid Activation code or invitation!");
        }
        $vendorId = $eventInvitationData['vendor_id'];
        $eventId = $eventInvitationData['event_id'];

        $customerData = $this->Customers->findByVendorId($vendorId)->first();

        //return $this->redirect(['controller' => '/users','action' => 'login']);
       
        if(!empty($customerData)){
            return $this->redirect(['controller' => '/users','action' => 'login']);
        }
        //prd($vendorData);
        
        //$this->layout = false;
        $user = $this->Customers->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Customers->patchEntity($user, $this->request->data);
            $user->customer_role = "vendor";
            $user->vendor_id     = $vendorId;
            if ($this->Customers->save($user)) {
                /* Auto Login after registration */
                $this->Auth->setUser($user->toArray());
                
               // $this->Flash->success(__('The vendor has been saved.'));
                return $this->redirect(['controller' => 'events','action' => 'view',encode($eventId)]);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }


}