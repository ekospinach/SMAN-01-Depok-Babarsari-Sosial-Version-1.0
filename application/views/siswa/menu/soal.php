<?php if($this->session->userdata('siswa_logged_in')){
  $data = $this->session->userdata;
    //check last class
  if(!empty($data['subkelas3'])){
    $subkelas = $data['subkelas3'];
  } else if(!empty($data['subkelas2'])){
    $subkelas = $data['subkelas2'];
  } else if(!empty($data['subkelas1'])){
    $subkelas = $data['subkelas1'];
  }
    //check my class
  $sql = "SELECT kelas.id_kelas AS 'class' FROM subkelas
  INNER JOIN kelas ON kelas.id_kelas = subkelas.kelas
  WHERE subkelas.id_subkelas = ? ";
  $myclass = $this->db->query($sql,$subkelas);
  $myclass = $myclass->row_array();
  $myclass = $myclass['class'];//result my last class
}
?>
<div class="message">
	<?php 
	$soal = $this->m_siswa->my_class_soal(10,0,$myclass);
	foreach($soal as $s):
		echo '<p>'.$s['guru'].'<br/>';
		echo '<a href="'.base_url('assets/assets/materi/'.$s['link']).'">'.$s['judul'].'</a></p>';
	endforeach;
	?>
	<hr width="100%"/>	
	<a href="<?php echo site_url('soal')?>">Semua Soal</a>
</div>