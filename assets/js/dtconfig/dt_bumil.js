configTabel["<?= $id ?>"] = [
    { 
        mData: null,
    },
    {
        data: 'nama_pencatat',
        mRender: function(_, t, row){
            var nama_pencatat = row['nama_pencatat'];
            var role = row['role'];

            return '<p><small>' + role + '</small> - ' + nama_pencatat + '</p>';
        }
    },
    {
        data: 'nama'
    },
    {
        data: 'suami'
    },
    {
        data: 'ttl',
        mRender: function (_, type, row) {
            var ttl = row.ttl;
            var estimasi = row.ttl_estimasi;

            return ttl + (estimasi == 1 ? '<span class ="badge badge-pill badge-sm bg-info">Estimasi</span>' : '');
        }
    },
    {
        data: 'domisili'
    },
    {
        data: 'alamat'
    },
    {
        data: 'pendidikan'
    },
    {
        data: 'pekerjaan'
    },
    
    {
        data: 'agama'
    }
];
