configTabel["<?= $id ?>"] = [
    {
        mData: null,
    },
    {
        data: 'id'
    },
    {
        data: 'dibuat'
    },
    {
        data: 'nama'
    },
    {
        mRender: function (_, type, row) {
            var el = '<ol>';
            row.kriteria.forEach(kriteria => {
                el += '<li>' + kriteria + '</li>';
            });
            el += '</ol>';
            
            return el;
        }
    },
    {
        mRender: function (_, type, row) {
            var el = '<ol>';
            row.subkriteria.forEach(subkriteria => {
                el += '<li>' + subkriteria + '</li>';
            });
            el += '</ol>';
            
            return el;
        }
    }
];
