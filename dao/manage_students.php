<?php
$error_code=0;#0=failure, 1=success
$msg="Message :";
if(isset($_POST['action']) && $_POST['action']=='add')
{
    try
    {
        //add to the database
        if(true)//check if commit true remove the true
        {
            //if no errors
            $msg.="System setting added successfully.";
            $error_code=1;
        }
        else
        {
            $msg.="An Error has occurred.(General error)";
        }
    }
    catch(Exception $ex)
    {
        //error
        $msg.="An Error has occurred. ".$ex->getMessage();
    }
    finally
    {
        //send response
        echo json_encode(array("error_code"=>$error_code,"message"=>$msg));
    }
}
if(isset($_POST['action']) && $_POST['action']=='edit')
{

}
if(isset($_POST['action']) && $_POST['action']=='delete')
{
    try
    {
        //add to the database
        if(true)//check if commit true remove the true
        {
            //if no errors
            $msg.="System setting added successfully.";
            $error_code=1;
        }
        else
        {
            $msg.="An Error has occurred.(General error)";
        }
    }
    catch(Exception $ex)
    {
        //error
        $msg.="An Error has occurred. ".$ex->getMessage();
    }
    finally
    {
        //send response
        echo json_encode(array("error_code"=>$error_code,"message"=>$msg));
    }

}