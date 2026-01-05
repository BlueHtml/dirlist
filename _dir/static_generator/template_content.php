<?php
if(!defined('GEN_INIT'))exit();
?>
<style>
	.btn-2 { padding:4px 10px;font-size:small; }
</style>
<div class="container" id="main">
    <div class="row mt-3">
        <div class="col-12">
            <p>
                当前位置：<?php
                    foreach($r['navi'] as $item){
                        // The links in 'navi' are like './?dir=/path', we need to convert them
                        if ($item['src'] === './') {
                            $href = $root_path . 'index.html';
                        } else {
                            $path = substr($item['src'], strlen('./?dir='));
                            $path = ltrim($path, '/');
                            $href = $root_path . $path . '/';
                        }
                        echo '<a href="'.$href.'">'.$item['name'].'</a> / ';
                    }
                ?>
            </p>
        </div>
    </div>
    <div class="row mt-2"><div class="col-12">
        <table class="table table-hover dirlist" id="list">
            <thead>
                <tr>
                <th>文件名</th>
                <th class="d-none d-lg-table-cell"></th>
                <th class="d-none d-lg-table-cell">修改时间</th>
                <th>大小</th>
                <th class="d-none d-md-table-cell">操作</th>
                </tr>
            </thead>
            <tbody>
<?php if($r['parent']){
    // Parent link needs to point to the parent's index.html
    $parent_href = ($r['dir'] === '.') ? './index.html' : '../';
?>
                <tr>
                    <td>
                        <a class="fname" href="<?php echo $parent_href; ?>"><i class="fa fa-level-up fa-fw"></i> ..</a>
                    </td>
                    <td class="d-none d-lg-table-cell"></td>
                    <td class="d-none d-md-table-cell">-</td>
                    <td>-</td>
                    <td class="d-none d-md-table-cell"></td>
                </tr>
<?php }
foreach($r['list'] as $item) {
    // File links are direct, dir links point to the subdirectory's index
    $href = $item['type'] === 'dir' ? './' . $item['name'] . '/' : './' . $item['name'];
?>
                <tr>
                    <td class="d-none d-md-table-cell">
                        <a class="fname" href="<?php echo $href; ?>" title="<?php echo $item['name']; ?>"><i class="fa <?php echo $item['icon']?> fa-fw"></i> <?php echo $item['name']; ?></a>
                    </td>
                     <td class="d-table-cell d-md-none">
                        <a class="fname" href="<?php echo $href; ?>" title="<?php echo $item['name']; ?>"><i class="fa <?php echo $item['icon']?> fa-fw"></i> <?php echo $item['name']; ?></a>
                    </td>
                    <td class="d-none d-lg-table-cell fileinfo">
                    <?php if($item['type'] == 'file'){ ?>
                        <a href="javascript:;" onclick="qrcode('<?php echo $href; ?>')" title="显示二维码"><i class="fa fa-qrcode" aria-hidden="true"></i></a>
                    <?php } ?>
                    </td>
                    <td class="d-none d-lg-table-cell"><?php echo $item['mtime']; ?></td>
                    <td><?php echo $item['size_format']; ?></td>
                    <td class="d-none d-md-table-cell">
                        <?php if($item['type'] == 'file'){ ?>
                            <a href="javascript:;" class="btn btn-sm btn-outline-secondary" title="复制链接" onclick="copy('<?php echo $href; ?>')"><i class="fa fa-link fa-fw"></i></a>
                            <a href="<?php echo $href; ?>" class="btn btn-sm btn-outline-primary" title="点击下载" download><i class="fa fa-download fa-fw"></i></a>
                            <?php if($item['view_type'] == 'image'){ ?><a class="btn btn-sm btn-outline-info" title="点此查看" href="javascript:;" onclick="view_image('<?php echo $href; ?>')"><i class="fa fa-eye fa-fw"></i></a>
                            <?php }elseif($item['view_type'] == 'audio'){ ?><a class="btn btn-sm btn-outline-info" title="点此播放" href="javascript:;" onclick="view_audio('<?php echo $href; ?>')"><i class="fa fa-play-circle fa-fw"></i></a>
                            <?php }elseif($item['view_type'] == 'video'){ ?><a class="btn btn-sm btn-outline-info" title="点此播放" href="javascript:;" onclick="view_video('<?php echo $item['name']; ?>','<?php echo $href; ?>')"><i class="fa fa-play-circle fa-fw"></i></a>
                            <?php }elseif($item['view_type'] == 'office'){ ?><a class="btn btn-sm btn-outline-info" title="点此查看" href="javascript:;" onclick="view_office('<?php echo $item['name']; ?>','<?php echo $href; ?>')"><i class="fa fa-eye fa-fw"></i></a>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>
<?php
}
?>
            </tbody>
        </table>
    </div></div>
<?php
if($r['readme_md']){
    $content = file_get_contents($r['readme_md']);
    if($content){
        require_once $parsedown_path;
        $Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
?>
    <div class="card mt-1">
        <div class="card-header">
        README.md
        </div>
        <div class="card-body">
            <div class="markdown-body">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
<?php	}
}
?>
</div>
<div id="aplayer"></div>
<script>
var audio_list = <?php echo json_encode($r['audio_list']);?>;
var aplayer;
</script>
