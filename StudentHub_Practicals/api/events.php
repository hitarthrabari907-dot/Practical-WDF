<?php
declare(strict_types=1);
require dirname(__DIR__).'/config/database.php';
require dirname(__DIR__).'/includes/helpers.php';
start_secure_session();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
function respond(int $status, array $data): never { http_response_code($status); echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit; }
function apiAudit(PDO $pdo, int $uid, string $action, string $details): void { try{$q=$pdo->prepare('INSERT INTO audit_logs(user_id,action,details) VALUES(?,?,?)');$q->execute([$uid,$action,$details]);}catch(Throwable $e){error_log($e->getMessage());} }
try { $pdo=getDatabaseConnection(); $method=$_SERVER['REQUEST_METHOD'];
 if ($method==='GET') { $q=trim((string)($_GET['q']??'')); $stmt=$pdo->prepare('SELECT event_id,title,description,event_date,location,capacity,category,status,poster_path FROM events WHERE title LIKE ? OR category LIKE ? ORDER BY event_date'); $like='%'.$q.'%';$stmt->execute([$like,$like]);respond(200,['success'=>true,'data'=>$stmt->fetchAll()]); }
 if (empty($_SESSION['student_id']) || ($_SESSION['role']??'student')!=='admin') respond(403,['success'=>false,'error'=>'Administrator access required.']);
 $token=$_SERVER['HTTP_X_CSRF_TOKEN']??''; if (!isset($_SESSION['csrf_token']) || !is_string($token) || !hash_equals($_SESSION['csrf_token'],$token)) respond(403,['success'=>false,'error'=>'Invalid CSRF token. Refresh the page and try again.']);
 if ($method!=='POST') { header('Allow: GET, POST'); respond(405,['success'=>false,'error'=>'Method not allowed.']); }
 $input=json_decode(file_get_contents('php://input'),true); if(!is_array($input))respond(400,['success'=>false,'error'=>'Request body must be valid JSON.']);
 $action=(string)($input['action']??''); $id=filter_var($input['event_id']??0,FILTER_VALIDATE_INT)?:0;
 if($action==='delete'){if($id<1)respond(422,['success'=>false,'error'=>'A valid event_id is required.']);$q=$pdo->prepare('DELETE FROM events WHERE event_id=?');$q->execute([$id]);if(!$q->rowCount())respond(404,['success'=>false,'error'=>'Event not found.']);apiAudit($pdo,(int)$_SESSION['student_id'],'event.api_delete','Deleted event ID '.$id);respond(200,['success'=>true,'message'=>'Event deleted.']);}
 if(!in_array($action,['add','update'],true))respond(422,['success'=>false,'error'=>'Action must be add, update, or delete.']);
 $title=trim((string)($input['title']??''));$description=trim((string)($input['description']??''));$date=trim((string)($input['event_date']??''));$location=trim((string)($input['location']??''));$capacity=filter_var($input['capacity']??100,FILTER_VALIDATE_INT);$category=trim((string)($input['category']??'Technical'));$status=(string)($input['status']??'open');
 if($title===''||mb_strlen($title)>150||$description===''||mb_strlen($description)>500||$location===''||mb_strlen($location)>150||!$date||strtotime($date)===false||!$capacity||$capacity<1||$capacity>50000||!in_array($category,['Technical','Workshop','Seminar','Cultural','Sports','Other'],true)||!in_array($status,['open','closed','coming_soon'],true))respond(422,['success'=>false,'error'=>'Please provide valid title, description, date, location, capacity, category and status.']);
 if($action==='add'){$q=$pdo->prepare('INSERT INTO events(title,description,event_date,location,capacity,category,status) VALUES(?,?,?,?,?,?,?)');$q->execute([$title,$description,date('Y-m-d H:i:s',strtotime($date)),$location,$capacity,$category,$status]);$id=(int)$pdo->lastInsertId();apiAudit($pdo,(int)$_SESSION['student_id'],'event.api_create','Created event: '.$title);respond(201,['success'=>true,'message'=>'Event added.','event_id'=>$id]);}
 if($id<1)respond(422,['success'=>false,'error'=>'A valid event_id is required for update.']);$q=$pdo->prepare('UPDATE events SET title=?,description=?,event_date=?,location=?,capacity=?,category=?,status=? WHERE event_id=?');$q->execute([$title,$description,date('Y-m-d H:i:s',strtotime($date)),$location,$capacity,$category,$status,$id]);if(!$q->rowCount()){$check=$pdo->prepare('SELECT event_id FROM events WHERE event_id=?');$check->execute([$id]);if(!$check->fetch())respond(404,['success'=>false,'error'=>'Event not found.']);}apiAudit($pdo,(int)$_SESSION['student_id'],'event.api_update','Updated event ID '.$id);respond(200,['success'=>true,'message'=>'Event updated.']);
} catch(Throwable $e){error_log($e->getMessage());respond(500,['success'=>false,'error'=>'Server or database error. Check the PHP error log and database schema.']);}
