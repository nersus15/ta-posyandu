configTabel["<?= $id ?>"] = [
    { 
        mData: null,
    },
    {
        data: 'nama_pencatat'
    },
    {
        data: 'nama'
    },
    {
        data: 'suami'
    },
    {
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
