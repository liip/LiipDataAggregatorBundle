<?php
namespace Liip\DataAggregatorBundle\Loaders;

use Assert\InvalidArgumentException;
use Doctrine\DBAL\Connection;
use Liip\DataAggregator\Loaders\LoaderInterface;
use Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityInterface;

class LoaderBoss implements LoaderInterface
{
    /**
     * Contains the instance of a database abstraction.
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbConnection;

    /**
     * Contains the instance of a value object.
     * @var \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityBoss
     */
    protected $loaderEntity;

    /**
     * @param \Doctrine\DBAL\Connection $databaseConnection
     * @param \Liip\DataAggregatorBundle\Loaders\Entities\LoaderEntityInterface $entity
     */
    public function __construct(Connection $databaseConnection, LoaderEntityInterface $entity)
    {
        $this->dbConnection = $databaseConnection;
        $this->loaderEntity = $entity;
    }

    /**
     * Starts the data loading process.
     *
     * @return array
     */
    public function load()
    {
        $query = <<<QUERY
SELECT 1 AS stufe,
        aktuell.bw_id||'0000000000'     AS boss_id,
        aktuell.bw_id                   AS bw_id,
        '00'                            AS bb_id,
        '0000'                          AS vs_id,
        '00'                            AS th_id,
        '00'                            AS fa_id,
        aktuell.bez_d                   AS bez_d,
        aktuell.bez_f                   AS bez_f,
        aktuell.bez_i                   AS bez_i,
        aktuell.bez_e                   AS bez_e,
        aktuell.aktuell_status          AS aktuell_status,
        aktuell.aktuell_gueltig_ab      AS aktuell_gueltig_ab,
        ''                              AS verantwortlich,
        ''                              AS sparte,
        folge.folge_status              AS folge_status,
        folge.folge_gueltig_ab          AS folge_gueltig_ab,
        aktuell. rpa_flag               AS rpa_flag
        FROM   (SELECT a.k_bw_id_int    AS a_bw_temp,
        a.boss_bw_id    AS bw_id,
        a.bezeichnung_d AS bez_d,
        a.bezeichnung_f AS bez_f,
        a.bezeichnung_i AS bez_i,
        a.bezeichnung_e AS bez_e,
        a_st.status     AS aktuell_status,
        a_st.gueltig_ab AS aktuell_gueltig_ab,
        a.rpa_flag AS rpa_flag
        FROM   gdst.v_boss_welt_2 a,
        gdst.v_boss_welt_st a_st
        WHERE  a.boss_bw_id LIKE '%'
        AND a.k_bw_id_int = a_st.k_bw_id_int
        AND To_char(a_st.gueltig_ab, 'yyyymmdd') = (SELECT
        To_char(MAX(st2.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_welt_st st2
        WHERE
        a.boss_bw_id LIKE '%'
        AND
        a.k_bw_id_int = st2.k_bw_id_int
        AND
        To_char(st2.gueltig_ab, 'yyyymmdd') <=
        To_char(SYSDATE, 'yyyymmdd'))) aktuell,
        (SELECT f.k_bw_id_int   AS f_bw_temp,
        f.boss_bw_id    AS bw_id,
        f.bezeichnung_d AS bez_d,
        f.bezeichnung_f AS bez_f,
        f.bezeichnung_i AS bez_i,
        f.bezeichnung_e AS bez_e,
        f_st.status     AS folge_status,
        f_st.gueltig_ab AS folge_gueltig_ab
        FROM   gdst.v_boss_welt_2 f,
        gdst.v_boss_welt_st f_st
        WHERE  f.boss_bw_id LIKE '%'
        AND f.k_bw_id_int = f_st.k_bw_id_int
        AND To_char(f_st.gueltig_ab, 'yyyymmdd') =
        (SELECT
        To_char(MIN(st3.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_bereich_st st3
        WHERE
        f.boss_bw_id LIKE '%'
        AND
        f.k_bw_id_int = st3.k_bw_id_int
        AND
        To_char(st3.gueltig_ab, 'yyyymmdd') >
        To_char(SYSDATE, 'yyyymmdd'))) folge
        WHERE  aktuell.a_bw_temp = folge.f_bw_temp(+)
        UNION
        SELECT 2                          AS stufe,
        aktuell.bw_id
        ||aktuell.bb_id
        ||'00000000'               AS boss_id,
        aktuell.bw_id              AS bw_id,
        aktuell.bb_id              AS bb_id,
        '0000'                     AS vs_id,
        '00'                       AS th_id,
        '00'                       AS fa_id,
        aktuell.bez_d              AS bez_d,
        aktuell.bez_f              AS bez_f,
        aktuell.bez_i              AS bez_i,
        aktuell.bez_e              AS bez_e,
        aktuell.aktuell_status     AS aktuell_status,
        aktuell.aktuell_gueltig_ab AS aktuell_gueltig_ab,
        (SELECT a_ver.verantwortlicher
        FROM   (SELECT verantwortlicher,
        k_bw_id_int,
        k_bb_id_int
        FROM   gdst.v_boss_bereich_ver ver
        ORDER  BY gueltig_ab DESC) a_ver
        WHERE  aktuell.bw_id = a_ver.k_bw_id_int
        AND aktuell.bb_id = a_ver.k_bb_id_int
        AND ROWNUM = 1)    AS verantwortlich,
        aktuell.sparte             AS sparte,
        folge.folge_status         AS folge_status,
        folge.folge_gueltig_ab     AS folge_gueltig_ab,
        aktuell.rpa_flag                         AS rpa_flag
        FROM   (SELECT a.k_bw_id_int        AS a_bw_temp,
        a.k_bb_id_int        AS a_bb_temp,
        a.boss_bw_id         AS bw_id,
        a.boss_bb_id         AS bb_id,
        a.bezeichnung_d      AS bez_d,
        a.bezeichnung_f      AS bez_f,
        a.bezeichnung_i      AS bez_i,
        a.bezeichnung_e      AS bez_e,
        a_st.status          AS aktuell_status,
        a_st.gueltig_ab      AS aktuell_gueltig_ab,
        a_sparte.bezeichnung AS sparte,
        a.rpa_flag AS rpa_flag
        FROM   gdst.v_boss_bereich_2 a,
        gdst.v_boss_bereich_st a_st,
        gdst.v_boss_bereich_spa a_spa,
        gdst.v_boss_sparte a_sparte
        WHERE  a.boss_bw_id LIKE '%'
        AND a_spa.sparte_id = a_sparte.sparte_id
        AND a.k_bw_id_int = a_st.k_bw_id_int
        AND a.k_bb_id_int = a_st.k_bb_id_int
        AND a.k_bw_id_int = a_spa.k_bw_id_int
        AND a.k_bb_id_int = a_spa.k_bb_id_int
        AND To_char(a_st.gueltig_ab, 'yyyymmdd') =
        (SELECT
        To_char(MAX(st2.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_bereich_st st2,
        gdst.v_boss_bereich_ver ver2,
        gdst.v_boss_bereich_spa spa2,
        gdst.v_boss_sparte sparte2
        WHERE
        a.boss_bw_id LIKE '%'
        AND
        a_spa.sparte_id = sparte2.sparte_id
        AND
        a.k_bw_id_int = st2.k_bw_id_int
        AND
        a.k_bb_id_int = st2.k_bb_id_int
        AND
        a.k_bw_id_int = ver2.k_bw_id_int
        AND
        a.k_bb_id_int = ver2.k_bb_id_int
        AND
        a.k_bw_id_int = spa2.k_bw_id_int
        AND
        a.k_bb_id_int = spa2.k_bb_id_int
        AND
        To_char(st2.gueltig_ab, 'yyyymmdd') <=
        To_char(SYSDATE, 'yyyymmdd'))) aktuell,
        (SELECT f.k_bw_id_int          AS f_bw_temp,
        f.k_bb_id_int          AS f_bb_temp,
        f.boss_bw_id           AS bw_id,
        f.boss_bb_id           AS bb_id,
        f.bezeichnung_d        AS bez_d,
        f.bezeichnung_f        AS bez_f,
        f.bezeichnung_i        AS bez_i,
        f.bezeichnung_e        AS bez_e,
        f_st.status            AS folge_status,
        f_st.gueltig_ab        AS folge_gueltig_ab,
        f_ver.verantwortlicher AS verantwortlich,
        f_sparte.bezeichnung   AS sparte
        FROM   gdst.v_boss_bereich_2 f,
        gdst.v_boss_bereich_st f_st,
        gdst.v_boss_bereich_ver f_ver,
        gdst.v_boss_bereich_spa f_spa,
        gdst.v_boss_sparte f_sparte
        WHERE  f.boss_bw_id LIKE '%'
        AND f_spa.sparte_id = f_sparte.sparte_id
        AND f.k_bw_id_int = f_st.k_bw_id_int
        AND f.k_bb_id_int = f_st.k_bb_id_int
        AND f.k_bw_id_int = f_ver.k_bw_id_int
        AND f.k_bb_id_int = f_ver.k_bb_id_int
        AND f.k_bw_id_int = f_spa.k_bw_id_int
        AND f.k_bb_id_int = f_spa.k_bb_id_int
        AND To_char(f_st.gueltig_ab, 'yyyymmdd') =
        (SELECT
        To_char(MIN(st3.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_bereich_st st3,
        gdst.v_boss_bereich_ver ver3,
        gdst.v_boss_bereich_spa spa3,
        gdst.v_boss_sparte sparte3
        WHERE
        f.boss_bw_id LIKE '%'
        AND
        spa3.sparte_id = sparte3.sparte_id
        AND
        f.k_bw_id_int = st3.k_bw_id_int
        AND
        f.k_bb_id_int = st3.k_bb_id_int
        AND
        f.k_bw_id_int = ver3.k_bw_id_int
        AND
        f.k_bb_id_int = ver3.k_bb_id_int
        AND
        f.k_bw_id_int = spa3.k_bw_id_int
        AND
        f.k_bb_id_int = spa3.k_bb_id_int
        AND
        To_char(st3.gueltig_ab, 'yyyymmdd') >
        To_char(SYSDATE, 'yyyymmdd'))) folge
        WHERE  aktuell.a_bw_temp = folge.f_bw_temp(+)
        AND aktuell.a_bb_temp = folge.f_bb_temp(+)
        UNION
        SELECT 3                          AS stufe,
        aktuell.bw_id
        ||aktuell.bb_id
        ||aktuell.vs_id
        ||'0000'                   AS boss_id,
        aktuell.bw_id              AS bw_id,
        aktuell.bb_id              AS bb_id,
        aktuell.vs_id              AS vs_id,
        '00'                       AS th_id,
        '00'                       AS fa_id,
        aktuell.bez_d              AS bez_d,
        aktuell.bez_f              AS bez_f,
        aktuell.bez_i              AS bez_i,
        aktuell.bez_e              AS bez_e,
        aktuell.aktuell_status     AS aktuell_status,
        aktuell.aktuell_gueltig_ab AS aktuell_gueltig_ab,
        ''                         AS verantwortlich,
        ''                         AS sparte,
        folge.folge_status         AS folge_status,
        folge.folge_gueltig_ab     AS folge_gueltig_ab,
        aktuell.rpa_flag           AS rpa_flag
        FROM   (SELECT a.k_bw_id_int   AS a_bw_temp,
        a.k_bb_id_int   AS a_bb_temp,
        a.k_vs_id_int   AS a_vs_temp,
        a.boss_bw_id    AS bw_id,
        a.boss_bb_id    AS bb_id,
        a.boss_vs_id    AS vs_id,
        a.bezeichnung_d AS bez_d,
        a.bezeichnung_f AS bez_f,
        a.bezeichnung_i AS bez_i,
        a.bezeichnung_e AS bez_e,
        a_st.status     AS aktuell_status,
        a_st.gueltig_ab AS aktuell_gueltig_ab,
        a.rpa_flag      AS rpa_flag
        FROM   gdst.v_boss_sektor_2 a,
        gdst.v_boss_sektor_st a_st
        WHERE  a.boss_bw_id LIKE '%'
        AND a.boss_bb_id LIKE '%'
        AND a.k_bw_id_int = a_st.k_bw_id_int
        AND a.k_bb_id_int = a_st.k_bb_id_int
        AND a.k_vs_id_int = a_st.k_vs_id_int
        AND To_char(a_st.gueltig_ab, 'yyyymmdd') = (SELECT
        To_char(MAX(st2.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_sektor_st st2
        WHERE
        a.k_bw_id_int = st2.k_bw_id_int
        AND
        a.k_bb_id_int = st2.k_bb_id_int
        AND
        a.k_vs_id_int = st2.k_vs_id_int
        AND
        To_char(st2.gueltig_ab, 'yyyymmdd') <=
        To_char(SYSDATE, 'yyyymmdd'))) aktuell,
        (SELECT f.k_bw_id_int   AS f_bw_temp,
        f.k_bb_id_int   AS f_bb_temp,
        f.k_vs_id_int   AS f_vs_temp,
        f.boss_bw_id    AS bw_id,
        f.boss_bb_id    AS bb_id,
        f.boss_vs_id    AS vs_id,
        f.bezeichnung_d AS bez_d,
        f.bezeichnung_f AS bez_f,
        f.bezeichnung_i AS bez_i,
        f.bezeichnung_e AS bez_e,
        f_st.status     AS folge_status,
        f_st.gueltig_ab AS folge_gueltig_ab
        FROM   gdst.v_boss_sektor_2 f,
        gdst.v_boss_sektor_st f_st
        WHERE  f.boss_bw_id LIKE '%'
        AND f.boss_bb_id LIKE '%'
        AND f.k_bw_id_int = f_st.k_bw_id_int
        AND f.k_bb_id_int = f_st.k_bb_id_int
        AND f.k_vs_id_int = f_st.k_vs_id_int
        AND To_char(f_st.gueltig_ab, 'yyyymmdd') = (SELECT
        To_char(MIN(st3.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_sektor_st st3
        WHERE
        f.k_bw_id_int = st3.k_bw_id_int
        AND
        f.k_bb_id_int = st3.k_bb_id_int
        AND
        f.k_vs_id_int = st3.k_vs_id_int
        AND
        To_char(st3.gueltig_ab, 'yyyymmdd') >
        To_char(SYSDATE, 'yyyymmdd'))) folge
        WHERE  aktuell.a_bw_temp = folge.f_bw_temp(+)
        AND aktuell.a_bb_temp = folge.f_bb_temp(+)
        AND aktuell.a_vs_temp = folge.f_vs_temp(+)
        UNION
        SELECT 4                          AS stufe,
        aktuell.bw_id
        ||aktuell.bb_id
        ||aktuell.vs_id
        ||aktuell.th_id
        ||'00'                     AS boss_id,
        aktuell.bw_id              AS bw_id,
        aktuell.bb_id              AS bb_id,
        aktuell.vs_id              AS vs_id,
        aktuell.th_id              AS th_id,
        '00'                       AS fa_id,
        aktuell.bez_d              AS bez_d,
        aktuell.bez_f              AS bez_f,
        aktuell.bez_i              AS bez_i,
        aktuell.bez_e              AS bez_e,
        aktuell.aktuell_status     AS aktuell_status,
        aktuell.aktuell_gueltig_ab AS aktuell_gueltig_ab,
        ''                         AS verantwortlich,
        ''                         AS sparte,
        folge.folge_status         AS folge_status,
        folge.folge_gueltig_ab     AS folge_gueltig_ab,
        aktuell.rpa_flag           AS rpa_flag
        FROM   (SELECT a.k_bw_id_int   AS a_bw_temp,
        a.k_bb_id_int   AS a_bb_temp,
        a.k_vs_id_int   AS a_vs_temp,
        a.k_th_id_int   AS a_th_temp,
        a.boss_bw_id    AS bw_id,
        a.boss_bb_id    AS bb_id,
        a.boss_vs_id    AS vs_id,
        a.boss_th_id    AS th_id,
        a.bezeichnung_d AS bez_d,
        a.bezeichnung_f AS bez_f,
        a.bezeichnung_i AS bez_i,
        a.bezeichnung_e AS bez_e,
        a_st.status     AS aktuell_status,
        a_st.gueltig_ab AS aktuell_gueltig_ab,
        a.rpa_flag      AS rpa_flag
        FROM   gdst.v_boss_thema_2 a,
        gdst.v_boss_thema_st a_st
        WHERE  a.boss_bw_id LIKE '%'
        AND a.boss_bb_id LIKE '%'
        AND a.boss_vs_id LIKE '%'
        AND a.boss_th_id LIKE '%'
        AND a.k_bw_id_int = a_st.k_bw_id_int
        AND a.k_bb_id_int = a_st.k_bb_id_int
        AND a.k_vs_id_int = a_st.k_vs_id_int
        AND a.k_th_id_int = a_st.k_th_id_int
        AND To_char(a_st.gueltig_ab, 'yyyymmdd') = (SELECT
        To_char(MAX(st2.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_thema_st st2
        WHERE
        a.k_bw_id_int = st2.k_bw_id_int
        AND
        a.k_bb_id_int = st2.k_bb_id_int
        AND
        a.k_vs_id_int = st2.k_vs_id_int
        AND
        a.k_th_id_int = st2.k_th_id_int
        AND
        To_char(st2.gueltig_ab, 'yyyymmdd') <=
        To_char(SYSDATE, 'yyyymmdd'))) aktuell,
        (SELECT f.k_bw_id_int   AS f_bw_temp,
        f.k_bb_id_int   AS f_bb_temp,
        f.k_vs_id_int   AS f_vs_temp,
        f.k_th_id_int   AS f_th_temp,
        f.boss_bw_id    AS bw_id,
        f.boss_bb_id    AS bb_id,
        f.boss_vs_id    AS vs_id,
        f.boss_th_id    AS th_id,
        f.bezeichnung_d AS bez_d,
        f.bezeichnung_f AS bez_f,
        f.bezeichnung_i AS bez_i,
        f.bezeichnung_e AS bez_e,
        f_st.status     AS folge_status,
        f_st.gueltig_ab AS folge_gueltig_ab
        FROM   gdst.v_boss_thema_2 f,
        gdst.v_boss_thema_st f_st
        WHERE  f.boss_bw_id LIKE '%'
        AND f.boss_bb_id LIKE '%'
        AND f.boss_vs_id LIKE '%'
        AND f.boss_th_id LIKE '%'
        AND f.k_bw_id_int = f_st.k_bw_id_int
        AND f.k_bb_id_int = f_st.k_bb_id_int
        AND f.k_vs_id_int = f_st.k_vs_id_int
        AND f.k_th_id_int = f_st.k_th_id_int
        AND To_char(f_st.gueltig_ab, 'yyyymmdd') = (SELECT
        To_char(MIN(st3.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_thema_st st3
        WHERE
        f.k_bw_id_int = st3.k_bw_id_int
        AND
        f.k_bb_id_int = st3.k_bb_id_int
        AND
        f.k_vs_id_int = st3.k_vs_id_int
        AND
        f.k_th_id_int = st3.k_th_id_int
        AND
        To_char(st3.gueltig_ab, 'yyyymmdd') >
        To_char(SYSDATE, 'yyyymmdd'))) folge
        WHERE  aktuell.a_bw_temp = folge.f_bw_temp(+)
        AND aktuell.a_bb_temp = folge.f_bb_temp(+)
        AND aktuell.a_vs_temp = folge.f_vs_temp(+)
        AND aktuell.a_th_temp = folge.f_th_temp(+)
        UNION
        SELECT 5                          AS stufe,
        aktuell.bw_id
        ||aktuell.bb_id
        ||aktuell.vs_id
        ||aktuell.th_id
        ||aktuell.fa_id            AS boss_id,
        aktuell.bw_id              AS bw_id,
        aktuell.bb_id              AS bb_id,
        aktuell.vs_id              AS vs_id,
        aktuell.th_id              AS th_id,
        aktuell.fa_id              AS fa_id,
        aktuell.bez_d              AS bez_d,
        aktuell.bez_f              AS bez_f,
        aktuell.bez_i              AS bez_i,
        aktuell.bez_e              AS bez_e,
        aktuell.aktuell_status     AS aktuell_status,
        aktuell.aktuell_gueltig_ab AS aktuell_gueltig_ab,
        ''                         AS verantwortlich,
        ''                         AS sparte,
        folge.folge_status         AS folge_status,
        folge.folge_gueltig_ab     AS folge_gueltig_ab,
        aktuell.rpa_flag           AS rpa_flag
        FROM   (SELECT a.k_bw_id_int   AS a_bw_temp,
        a.k_bb_id_int   AS a_bb_temp,
        a.k_vs_id_int   AS a_vs_temp,
        a.k_th_id_int   AS a_th_temp,
        a.k_fa_id_int   AS a_fa_temp,
        a.boss_bw_id    AS bw_id,
        a.boss_bb_id    AS bb_id,
        a.boss_vs_id    AS vs_id,
        a.boss_th_id    AS th_id,
        a.boss_fa_id    AS fa_id,
        a.bezeichnung_d AS bez_d,
        a.bezeichnung_f AS bez_f,
        a.bezeichnung_i AS bez_i,
        a.bezeichnung_e AS bez_e,
        a_st.status     AS aktuell_status,
        a_st.gueltig_ab AS aktuell_gueltig_ab,
        a.rpa_flag      AS rpa_flag
        FROM   gdst.v_boss_familie_2 a,
        gdst.v_boss_familie_st a_st
        WHERE  a.boss_bw_id LIKE '%'
        AND a.boss_bb_id LIKE '%'
        AND a.boss_vs_id LIKE '%'
        AND a.boss_th_id LIKE '%'
        AND a.boss_fa_id LIKE '%'
        AND a.k_bw_id_int = a_st.k_bw_id_int
        AND a.k_bb_id_int = a_st.k_bb_id_int
        AND a.k_vs_id_int = a_st.k_vs_id_int
        AND a.k_th_id_int = a_st.k_th_id_int
        AND a.k_fa_id_int = a_st.k_fa_id_int
        AND To_char(a_st.gueltig_ab, 'yyyymmdd') =
        (SELECT
        To_char(MAX(st2.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_familie_st st2
        WHERE
        a.k_bw_id_int = st2.k_bw_id_int
        AND
        a.k_bb_id_int = st2.k_bb_id_int
        AND
        a.k_vs_id_int = st2.k_vs_id_int
        AND
        a.k_th_id_int = st2.k_th_id_int
        AND
        a.k_fa_id_int = st2.k_fa_id_int
        AND
        To_char(st2.gueltig_ab, 'yyyymmdd') <=
        To_char(SYSDATE, 'yyyymmdd'))) aktuell,
        (SELECT f.k_bw_id_int   AS f_bw_temp,
        f.k_bb_id_int   AS f_bb_temp,
        f.k_vs_id_int   AS f_vs_temp,
        f.k_th_id_int   AS f_th_temp,
        f.k_fa_id_int   AS f_fa_temp,
        f.boss_bw_id    AS bw_id,
        f.boss_bb_id    AS bb_id,
        f.boss_vs_id    AS vs_id,
        f.boss_th_id    AS th_id,
        f.boss_fa_id    AS fa_id,
        f.bezeichnung_d AS bez_d,
        f.bezeichnung_f AS bez_f,
        f.bezeichnung_i AS bez_i,
        f.bezeichnung_e AS bez_e,
        f_st.status     AS folge_status,
        f_st.gueltig_ab AS folge_gueltig_ab
        FROM   gdst.v_boss_familie_2 f,
        gdst.v_boss_familie_st f_st
        WHERE  f.boss_bw_id LIKE '%'
        AND f.boss_bb_id LIKE '%'
        AND f.boss_vs_id LIKE '%'
        AND f.boss_th_id LIKE '%'
        AND f.boss_fa_id LIKE '%'
        AND f.k_bw_id_int = f_st.k_bw_id_int
        AND f.k_bb_id_int = f_st.k_bb_id_int
        AND f.k_vs_id_int = f_st.k_vs_id_int
        AND f.k_th_id_int = f_st.k_th_id_int
        AND f.k_fa_id_int = f_st.k_fa_id_int
        AND To_char(f_st.gueltig_ab, 'yyyymmdd') =
        (SELECT
        To_char(MIN(st3.gueltig_ab), 'yyyymmdd')
        FROM
        gdst.v_boss_familie_st st3
        WHERE
        f.k_bw_id_int = st3.k_bw_id_int
        AND
        f.k_bb_id_int = st3.k_bb_id_int
        AND
        f.k_vs_id_int = st3.k_vs_id_int
        AND
        f.k_th_id_int = st3.k_th_id_int
        AND
        f.k_fa_id_int = st3.k_fa_id_int
        AND
        To_char(st3.gueltig_ab, 'yyyymmdd') >
        To_char(SYSDATE, 'yyyymmdd'))) folge
        WHERE  aktuell.a_bw_temp = folge.f_bw_temp(+)
        AND aktuell.a_bb_temp = folge.f_bb_temp(+)
        AND aktuell.a_vs_temp = folge.f_vs_temp(+)
        AND aktuell.a_th_temp = folge.f_th_temp(+)
        AND aktuell.a_fa_temp = folge.f_fa_temp(+)
        ORDER  BY bw_id ASC,
        bb_id ASC,
        vs_id ASC,
        th_id ASC,
        fa_id ASC
QUERY;

        $result = $this->dbConnection->fetchAll($query);

        return $this->process($result);
    }

    /**
     * Converts data to an Object
     *
     * @param array $data
     *
     * @return array
     */
    protected function process(array $data)
    {
        $processedData = array();

        if (!empty($data)) {
            foreach ($data as $item) {
                try {
                    $entity = clone $this->loaderEntity;
                    $processedData[] = $entity->init($item);
                    unset($entity);

                } catch (InvalidArgumentException $e) {
                    // ToDo: Log here
                }
            }
        }

        return $processedData;
    }

    /**
     * Backport to the DataAggregator.
     * This backport shall enable the DataAggregator to decide if to stop
     * over other registered loaders.
     * @return boolean
     */
    public function stopPropagation()
    {
        return false;
    }
}
