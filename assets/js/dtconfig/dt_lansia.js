var role = "<?= isset($role) ? $role : '' ?>";
var dtid = "<?= $id ?>";
configTabel[dtid] = [
    { 
        mData: null,
    },
    {
        data: 'nama'
    },
    {
        data: 'alamat'
    },
    {
        mRender: function (_, type, row) {
            var ttl = row.ttl;
            var estimasi = row.estimasi_ttl;

            return ttl + (estimasi == 1 ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
        }
    },
    {
        data: 'nik'
    }
];

if(role == 'admin')
    configTabel[dtid].splice(1, 0, {data: 'nama_pencatat'});