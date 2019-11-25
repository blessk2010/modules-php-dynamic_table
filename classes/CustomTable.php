<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/16/2019
 * Time: 9:45 AM
 */

class CustomTable
{
    public $page_header;
    public $table_class; #optional for displaying export menus default customTable otherwise can use customTable2 which has export options
    public $create_function;
    public $action_array;
    public $show_search;
    public $show_first_column; #for some tables with action column first column is assumed to hold ids default hidden
    public function __construct($header,$table_class="customTable",$show_first_column=false,$show_search=true)
    {
        $this->page_header=$header;
        $this->create_function=NULL;
        $this->action_array=NULL;
        $this->table_class=$table_class;
        $this->show_first_column=$show_first_column;
        $this->show_search=$show_search;
        $this->setPageHeader($this->page_header);
    }

    public function setCreateFunction($create_function, $button_text, $icon="fa-plus")
    {
        $this->create_function = $create_function;
        echo "<div class='row'>
                    <div class='col s12'>
                        <label class='control-label'><button type='button' id='create_button' class='btn btn-success' data-info='create_record' onClick='".$create_function."'><i class='fa ".$icon."'></i> ".$button_text."</button></label>
                    </div>
                </div>";
    }

    public function setActionArray($action_array)
    {
        $this->action_array=$action_array; #multi-dimension array holding action column properties
    }
    public function setPageHeader()
    {
        echo "<div class='block-header page-title'><h2>".$this->page_header."</h2></div>";
        echo "<div class='row'>";
        //print hidden form
        echo "
              <form id='hidden_form' method='post' action='./".basename($_SERVER['PHP_SELF'])."'>
                    <input type='hidden' name='hidden_id' id='hidden_id'/>
                    <input type='hidden' name='hidden_action' id='hidden_action'/>
               </form>
        ";
        echo "</div>";
    }
    public function getCustomTable($db_conn,$query, $parameter=NULL)
    {
        try
        {
            $results=$db_conn->prepare($query);
            #binding parameters if any, null is bonded as a value
            if(isset($parameter))
            {
                for($i=0; $i<count($parameter); $i++)
                {
                    if($parameter[$i]!=NULL)
                        $results->bindParam($i+1, $parameter[$i]); #indexed parameter starts @ pos 1
                    else
                        $results->bindValue($i+1, $parameter[$i]); #indexed parameter starts @ pos 1
                }
            }
            $results->execute();
            ##########################################Build table#######################################################################
            #get columns from data set
            $cols=array();
            for($i=0; $i<$results->columnCount(); $i++)
            {
                $col=$results->getColumnMeta($i);
                $cols[]=$col['name'];
            }

            #build table
            echo "<div class='row'>";
            echo "<div class='col s12 m12 l12'>";
            echo "<div class='card'>";
            echo "<div class='card-content'>";
            echo "<table class='display responsive-table pull-left ".$this->table_class." condensed bordered striped highlight table-hover table-sm table-small-font compact'>";
            echo "<thead class='thead-inverse'>";
            $is_first_col=$this->show_first_column; #skip first col which is table id when needed default hidden
            echo "<tr>";
            foreach($cols as $data)
            {
                if($is_first_col)
                {
                    echo "<th style=' white-space: normal;' class='info'>".$data."</th>";
                }
                else
                {
                    $is_first_col=true;
                }
            }
            //action cols
            if(isset($this->action_array))
            {
                $this->addActionHeader();
            }
            echo "</tr>";
            $is_first_col=$this->show_first_column;
            if($this->show_search)
            {
                echo "<tr>";
                foreach($cols as $data)
                {
                    #data cols with filter
                    if($is_first_col)
                    {
                        echo "<th style=' white-space: normal' class='info filterrow'>".$data."</th>";
                    }
                    else
                    {
                        $is_first_col=true;
                    }
                }
                //action cols no filter
                if(isset($this->action_array))
                {
                    $this->addActionHeaderFilter();
                }
                echo "</tr>";
            }
            echo "</thead>";
            echo "<tbody>";#body of the table
            while($row=$results->fetch(PDO::FETCH_NUM))
            {
                echo "<tr>";
                $is_first_col=$this->show_first_column;
                $row_id="";
                for($i=0;$i<$results->columnCount();$i++)
                {
                    if($is_first_col)
                    {
                        echo "<td  style=' white-space: normal;'>".$row[$i]."</td>";
                    }
                    else
                    {
                        $is_first_col=true;
                        $row_id=$row[$i];
                    }
                }
                //action cols data
                if(isset($this->action_array))
                {
                    $this->addActionCol($row_id);
                }
                echo "</tr>";
            }
            echo "</tbody>";#body of the table
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            ##########################################Build table######################################################################

        }
        catch(Exception $e)
        {
            echo "System error: Unable to get data. ";#.$e->getMessage();
        }
    }

    public function getCustomTableWithHeader($db_conn,$query, $parameter=NULL, $header="")
    {
        try
        {
            $results=$db_conn->prepare($query);
            if(isset($parameter))
            {
                $this->bindParameter($results,$parameter);
            }
            $results->execute();
            ##########################################Build table#######################################################################
            #get columns from data set
            $cols=array();
            for($i=0; $i<$results->columnCount(); $i++)
            {
                $col=$results->getColumnMeta($i);
                $cols[]=$col['name'];
            }

            #build table
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            #echo "<div class='table-responsive'>";
            echo "<table class='table table-responsive  table-condensed table-bordered table-stripped table-hover table-sm table-small-font compact'>";
            echo "<thead class='thead-inverse'>";
            $is_first_col=$this->show_first_column; #skip first col which is table id
            echo "<tr>";
            echo "<th class='info text-center' colspan='".(count($cols)+count($this->action_array))."'>".$header."</th>";
            echo "</tr>";
            echo "<tr>";
            foreach($cols as $data)
            {
                if($is_first_col)
                {
                    echo "<th class='info'>".$data."</th>";
                }
                else
                {
                    $is_first_col=true;
                }
            }
            //action cols
            if(isset($this->action_array))
            {
                $this->addActionHeader();
            }
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";#body of the table
            while($row=$results->fetch(PDO::FETCH_NUM))
            {
                echo "<tr>";
                $is_first_col=$this->show_first_column;
                $row_id="";
                for($i=0;$i<$results->columnCount();$i++)
                {
                    if($is_first_col)
                    {
                        echo "<td>".$row[$i]."</td>";
                    }
                    else
                    {
                        $is_first_col=true;
                        $row_id=$row[$i];
                    }
                }
                //action cols data
                if(isset($this->action_array))
                {
                    $this->addActionCol($row_id);
                }
                echo "</tr>";
            }
            echo "</tbody>";#body of the table
            echo "</table>";
            #echo "</div>";
            echo "</div>";
            echo "</div>";
            ##########################################Build table######################################################################

        }
        catch(Exception $e)
        {
            echo "System error: Unable to get data. ";#.$e->getMessage();
        }
    }
    public function addActionHeader()
    {
        foreach($this->action_array AS $array_data)
        {
            echo "<th style='text-align:center' class='info text-center'>".$array_data['header_text']."</th>";
        }
    }
    public function addActionHeaderFilter()
    {
        foreach($this->action_array AS $array_data)
        {
            echo "<th class='info'></th>";
        }
    }
    public function addActionCol($row_id)
    {
        $i=0;#for generating distinct ids
        foreach($this->action_array AS $array_data)
        {
            echo "<td style='text-align:center' class='text-center ".$array_data['icon_class']." nopadding'><label class='action_class control-label' data-info='".$row_id."' id='".$row_id.$i."' onClick='".$array_data['function']."'><i class='fa ".$array_data['icon']."' title='".$array_data['header_text']."'></i></label></td>";
            $i++;
        }
    }
    public function bindParameter(&$results,&$parameter)
    {
        #binding parameters if any, null is bonded as a value
        if(isset($parameter))
        {
            for($i=0; $i<count($parameter); $i++)
            {
                if($parameter[$i]!=NULL)
                    $results->bindParam($i+1, $parameter[$i]); #indexed parameter starts @ pos 1
                else
                    $results->bindValue($i+1, $parameter[$i]); #indexed parameter starts @ pos 1
            }
        }
    }
}
?>