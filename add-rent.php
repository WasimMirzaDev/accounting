<?php

$u  = 'fiftaeer_ams';
$db = 'fiftaeer_ams';
$p  = '#*2NnFVwmwMh';

$con = mysqli_connect('localhost', $u, $p, $db);

if($con)
{
    echo 'connected';
}
$query = "
select e.id as en_id, tu.unit_id, t.id as tenant_id, s.name, t.weekly_daynum, t.monthly_datenum, t.yearly_month_date, s.no_days,
(select 
 count(*) from challans 
 inner join challan_details ON challans.id = challan_details.challan_id
 inner join fee_heads as fh on fh.fh_id = challan_details.fh_id and fh.is_rent = 1
 where i_date between DATE_SUB(curdate(), INTERVAL s.no_days-1 DAY) and curdate() and challans.tenant_id = t.id and challans.unit_id = tu.unit_id
 and fh.is_rent = 1
) as total
FROM tenants AS t
LEFT OUTER JOIN payment_schedules AS s ON s.c_id = t.pm_type
INNER JOIN tenant_units as tu ON tu.tenant_id = t.id
inner join enrolments as e ON e.unit_id = tu.unit_id and e.tenant_id = tu.tenant_id and e.active = 1
having total = 0
";

$exe = mysqli_query($con, $query);
while($d = mysqli_fetch_assoc($exe))
{
    
        extract($d);
    
    $date = date('Y-m-d');
    
    $q = "INSERT INTO challans (date, i_date, l_date, tenant_id, unit_id, remarks) 
                             values('$date', '$date', '$date', '$tenant_id', '$unit_id', '')";  
    if(mysqli_query($con, $q))
    {
        $challan_id = mysqli_insert_id($con);
        
        $qd = "
        INSERT INTO challan_details (challan_id, fh_id, fh_amount)
        SELECT $challan_id, fh_id, amount FROM enrolmentd WHERE enrolment_id = $en_id;
        ";
        if(mysqli_query($con, $qd))
        {
            echo 'success';    
        }
        else 
        {
            echo $qd;
        }
        
    }
    else 
    {
        echo 'failed';
    }
    
    echo "<br/>";

//   echo create_challan($con, $r);    
}

?>