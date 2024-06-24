var role = "<?= isset($role) ? $role : '' ?>";
var dtid = "<?= $id ?>";
configTabel[dtid] = [
    { 
        mData: null,
    },
    {
        data: 'nama_pencatat',
        mRender: function(_, t, row){
            var nama_pencatat = row['nama_pencatat'];
            var role = row['role_pencatat'];

            return '<p><small>' + role + '</small> - ' + nama_pencatat + '</p>';
        }
    },
    {
        data: 'nama'
    },
    {
        data: 'alamat'
    },
    {
        data: 'ttl',
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

// if(role == 'admin')
//     configTabel[dtid].splice(1, 0, {data: 'nama_pencatat'});