Highcharts.chart('organizationChart', {
    chart: {
        height: 600,
        inverted: true
    },

    title: {
        text: 'Struktur Kepengurusan CIPTA SEJATI <br>Kotawaringin Timur - Kalimantan Tengah'
    },

    accessibility: {
        point: {
            descriptionFormat: '{add index 1}. {toNode.name}' +
                '{#if (ne toNode.name toNode.id)}, {toNode.id}{/if}, ' +
                'reports to {fromNode.id}'
        }
    },

    series: [{
        type: 'organization',
        name: 'Highsoft',
        keys: ['from', 'to'],
        data: [
            ['Shareholders', 'Board'],
            ['Board', 'CEO'],
            ['CEO', 'CTO'],
            ['CEO', 'CPO'],
            ['CEO', 'CSO'],
            ['CEO', 'HR'],
            ['CTO', 'Product'],
            ['CTO', 'Web'],
            ['CSO', 'Sales'],
            ['HR', 'Market'],
            ['CSO', 'Market'],
            ['HR', 'Market'],
            ['CTO', 'Market']
        ],
        levels: [{
            level: 0,
            color: 'silver',
            dataLabels: {
                color: 'black'
            },
            height: 25
        }, {
            level: 1,
            color: 'silver',
            dataLabels: {
                color: 'black'
            },
            height: 25
        }, {
            level: 2,
            color: '#980104'
        }, {
            level: 4,
            color: '#359154'
        }],
        nodes: [{
            id: 'Shareholders'
        }, {
            id: 'Board'
        }, {
            id: 'CEO',
            title: 'CEO',
            name: 'Atle Sivertsen',
            image: './assets/images/daftaranggota/Kotawaringin Timur/Husni Aditya_001/husni.jpg'
        }, {
            id: 'HR',
            title: 'CFO',
            name: 'Anne Jorunn Fjærestad',
            color: '#007ad0',
            image: './assets/images/daftaranggota/Kotawaringin Timur/Agus Riyanto_006/My.png'
        }, {
            id: 'CTO',
            title: 'CTO',
            name: 'Christer Vasseng',
            image: './assets/images/daftaranggota/default/avatar.png'
        }, {
            id: 'CPO',
            title: 'CPO',
            name: 'Torstein Hønsi',
            image: './assets/images/daftaranggota/Seruyan/anu_123/Screenshot 2023-11-01 114133.png'
        }, {
            id: 'CSO',
            title: 'CSO',
            name: 'Anita Nesse',
            image: './assets/images/daftaranggota/Gresik/Siti Aulia Pratama_437/avatar3.jpg'
        }, {
            id: 'Product',
            name: 'Product developers'
        }, {
            id: 'Web',
            name: 'Web devs, sys admin'
        }, {
            id: 'Sales',
            name: 'Sales team'
        }, {
            id: 'Market',
            name: 'Marketing team',
            column: 5
        }],
        colorByPoint: false,
        color: '#007ad0',
        dataLabels: {
            color: 'white'
        },
        borderColor: 'white',
        nodeWidth: 65
    }],
    tooltip: {
        outside: true
    },
    exporting: {
        allowHTML: true,
        sourceWidth: 800,
        sourceHeight: 600
    }

});