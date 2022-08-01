<?php

require_once('/var/www/html/classlib/Code.php');

class XMLreport {
    
    public string $xfile;
    public string $xmlstr;
    public string $reportid;
    public string $domain;
    public SimpleXMLElement $xmlObject;
    public SimpleXMLElement $records;
    
    public function __construct(string $xfile) {
        if ( !file_exists($xfile) ) {
            throw new Exception('File not found.');
        }
        $this->xfile = $xfile;
    }
    
    public function read(){
    
        //if(true){throw new Exception('This exception will always be thrown.');}
            
        $filename=$this->xfile;

        $theFile = fopen($filename, "r");
        if(!$theFile){
            throw new Exception('Failed to open theFile.');
        }
        $this->xmlstr = fread($theFile, filesize($filename));
        fclose($theFile);
            
    }//end function read
    
    
    public function parse(){

        $this->xmlObject = new SimpleXMLElement($this->xmlstr);
        $report = $this->xmlObject;
        
        if($report->report_metadata->report_id && $this->domain = $report->policy_published->domain){
            $this->reportid = $report->report_metadata->report_id;
            $this->domain = $report->policy_published->domain;
            $this->orgname = $report->report_metadata->org_name;
            $this->start = $report->report_metadata->date_range->begin;
            $this->end = $report->report_metadata->date_range->end;
            
            $this->records = $report->record;
            return TRUE;
        }else{
            return FALSE;
        }
            
    }//end function parse
    
    public function todb($db){
        $inserted=0;
        $notinserted=0;
            
        foreach ($this->records as $record){
            
            $unique = Code::get16chars();
            $reportid = $this->reportid;
            //echo $reportid;
            $domain = $this->domain;
            $orgname = $this->orgname;
            $start = $this->start;
            $end = $this->end;
            $sourceip = $record->row->source_ip;
            $count = $record->row->count;
            $disposition = $record->row->policy_evaluated->disposition;
            $dkimalign = $record->row->policy_evaluated->dkim;
            $spfalign = $record->row->policy_evaluated->spf;
            $headerfrom = $record->identifiers->header_from;
            $dkimdomain = 'not included';
            $dkimresult = 'not included';
            $selector  = 'not included';
            $spfdomain = $record->auth_results->spf->domain;
            $spfresult = $record->auth_results->spf->result;
            $dateread = date("D M j G:i:s T Y");
            
            if($record->auth_results->dkim->domain){
                $dkimdomain = $record->auth_results->dkim->domain;
                $dkimresult = $record->auth_results->dkim->result;
                if($record->auth_results->dkim->selector){
                    $selector = $record->auth_results->dkim->selector;
                }
            }
            
            $query = "INSERT INTO spfreports VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param('sssiisisssssssssss', $reportid, $domain, $orgname, $start, $end, $sourceip, $count, $disposition, $dkimalign, $spfalign, $headerfrom, $dkimdomain, $dkimresult, $selector, $spfdomain, $spfresult, $dateread, $unique);
            $stmt->execute();
            if($db ->affected_rows == 1){
                //Threshold
                $inserted++;
                //echo $inserted;
            }else{
                $notinserted++;
                //echo $notinserted;
                error_log('Failed to insert spfreport.  May be duplicate.  \n');
            }

        }//end foreach records as record
        
        $threshold = $inserted/($inserted + $notinserted);
        echo '...'.$threshold.'...';
        if($threshold > 0.5){
            return TRUE;
        }else{
            return FALSE;
        }
        
    }//end function todb

    public function dump(){
        var_dump($this->xmlObject);
    }//end function dump
    
}//end class XMLreport

?>
