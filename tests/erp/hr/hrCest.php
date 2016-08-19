<?php
namespace hr;

use \SuperUser;
use \Codeception\Util\Locator;

class hrCest
{
    public function _before(SuperUser $I)
    {
    }

    public function _after(SuperUser $I)
    {
    }

    
    public function test_default_non_editable_department_named_company(SuperUser $i)
    {
        $i->wantTo('Test if default "Company" named department is there and it is non editable');
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->Department');
        $i->see('Company',Locator::elementAt('//table/tbody/tr/td', 1));
        $i->dontSeeElementInDOM('table tbody tr:first-child  td:last-child a');

        $i->wantTo('Test Quick Search');
        
        $i->searchFor('comp');
        $i->see('Company',Locator::elementAt('//table/tbody/tr/td', 1));

        $i->searchFor('xxxxssssss');
        $i->see('No matching records found');

        $i->searchFor('');
        $i->dontSee('No matching records found');
        $i->see('Company',Locator::elementAt('//table/tbody/tr/td', 1));

        $i->click('Active');
        $i->waitPageLoad();
        $i->click('InActive');
        $i->waitPageLoad();
    }

    // function test_sorting(SuperUser $i){
    //     $i->see('NOT IMPLEMENTED');
    // }

    function test_add_department(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->waitPageLoad();
        $i->clickMenu('HR->Department');
        $i->waitPageLoad();
        $i->click('Add Department');

        $i->fillAtkField('name','Company');
        $i->click('Add');
        $i->waitForElement('.field-error-text');
        $i->see('Name value "Company" already exists');

        $i->fillAtkField('name','Desining');
        $i->click('Add');
        $i->waitForText('Production Level must be filled',10);
       
        $i->fillAtkField('production_level','initial_stage');
        $i->click('Add');
        $i->waitForText('Production_level must be an integer: eg 1234',5);

        $i->fillAtkField('production_level','0');
        $i->click('Add');
        $i->waitForText('Production_level must be greater than 0',5);


        $i->fillAtkField('production_level','1');
        $i->click('Add');
        $i->waitForPageLoad();
        $i->see('Desining',['css'=>'table tbody tr:nth-child(2) td:first-child']);

        // $i->click(['css'=>'table tbody tr:nth-child(2) td:nth-child(4) button']);
        // $i->click('Deactivate');
        // $i->waitForPageLoad();
        // $i->click(['css'=>'table tbody tr:nth-child(2) td:nth-child(4) button']);
        // $i->click('Activate');
        // $i->waitForPageLoad();
        // $i->see('Active',['css'=>'table tbody tr:nth-child(2) td:nth-child(4)']);

        $i->click(['css'=>'table tbody tr:nth-child(2) td:nth-child(5) a.pb_edit']);
        $i->waitForPageLoad();
        $i->see('Editing Department');

        $i->seeInField('[data-shortname=name]','Desining');
        $i->fillAtkField('name','Designing');
        $i->click('Save');
        $i->waitForPageLoad();
        $i->see('Designing');
        $i->dontSee('Desining');

        // Delete Test
        $i->click('Add Department');
        $i->waitForPageLoad();
        $i->fillAtkField('name','test to delete');
        $i->fillAtkField('production_level',2);
        $i->click('Add');
        $i->waitForPageLoad();
        $i->see('test to delete');

        $i->click(['css'=>'table tbody tr:nth-child(3) td:nth-child(5) a.do-delete']);
        $i->acceptPopup();
        $i->waitForPageLoad();
        $i->dontSee('test to delete');
        $i->waitForText('Deleted Successfully',5);

        $i->wait(5);
    }

    // function testACL(SuperUser $i){
    //     $i->login('management@xavoc.com');
    //     $i->changeACL('CEO','xepan\hr/Department',['Active'=>['view'=>'Selft only','edit'=>'None']]);
    // }

    function testPost(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->Post');
        $i->see('CEO');
        $i->click(['css'=>'table tbody tr td:nth-child(4) a.emails-accesible']);
        // $i->acceptPopup();
        $i->see('Permitted Emails');
        $i->click(['css'=>' button.editable-cancel']);
        $i->wait(2);
        $i->click(['css'=>'table tbody tr td:nth-child(5) a.do-view-post-employees']);
        $i->see('Post Employees');
        $i->wait(2);
        $i->click(['css'=>' button.ui-dialog-titlebar-close']);
        // $i->click(['css'=>'table tbody tr td:nth-child(1) a.do-view-employee']);
        // $i->see('Employee Details');
        // $i->wait(2);
        // $i->click(['css'=>' button.ui-dialog-titlebar-close']);
        // $i->see('Post Employees');
        $i->wait(2);
        $i->click(['css'=>'table tbody tr td:nth-child(6) button']);
    }
    function test_add_post(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->Post');
        $i->waitPageLoad();
        $i->see('Add Post');
        $i->click('Add Post');

        $i->fillAtkField('name','management');
        $i->click('Add');
        $i->waitForElement('.field-error-text');
        $i->see('Department_id must not be empty');
        $i->wait(10);
        $i->select2Option("department_id",['text'=>'Company']);
        $i->click('Add');
        $i->see('management');
    }
    function testEmployee(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->Employee');
        $i->see('Add Employee');
        $i->click(['css'=>'table tbody tr td:nth-child(1) a.do-view-employee']);
        $i->click('Official');
        $i->waitForText('Offer Date');
        $i->click('Activity');
        // $i->waitForText("deactivated ");
        $i->click('Documents');
        $i->waitForText('No document found');
    }
    
    function testEmployeeMovement(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->Employee Movement');
        // $i->waitPageLoad();
        $i->see('Employee Movement');
        
    }
    function testUser(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->User');
        // $i->waitPageLoad();
        $i->see('Add User');
    }
    function testAffiliate(SuperUser $i){
        $i->login('management@xavoc.com');
        $i->clickMenu('HR->Affiliate');
        // $i->waitPageLoad();
        $i->see('Add Affiliate');
    }


}
