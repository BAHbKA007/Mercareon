import pyodbc, requests, json, os, time

final_data = []

while True:
  try:
    cnxn = pyodbc.connect('DRIVER={SQL Server};SERVER=GR-SQL02\GRSQL02I1')
    cursor = cnxn.cursor()

    # 000 Gemüsering
    cursor.execute("""SELECT DISTINCT HAN.HANVORNR, HAN.HANADRNR, Kunde.KUNKUBEZ, HAN.HANKENNG, CONVERT(DATE, HAN.HANVORDT) AS Datum
      FROM [db_GRS_000].[dbo].[tbl_HAN] AS HAN
      JOIN db_GRS_000.dbo.tbl_KUN AS Kunde ON Kunde.KUNNR = HAN.HANADRNR
      WHERE HANVORDT = '2021-07-16T00:00:00'
      AND HAN.HANVORAR = '10'
      AND Kunde.KUNNR IN ('021496','021497','021498','021499','021500','021501')""")
    table_000 = cursor.fetchall()

    # 098 TOGAZ
    cursor.execute("""SELECT DISTINCT HAN.HANVORNR, HAN.HANADRNR, Kunde.KUNKUBEZ, HAN.HANKENNG, CONVERT(DATE, HAN.HANVORDT) AS Datum
      FROM [db_GRS_098].[dbo].[tbl_HAN] AS HAN
      JOIN db_GRS_098.dbo.tbl_KUN AS Kunde ON Kunde.KUNNR = HAN.HANADRNR
      WHERE HANVORDT = '2021-07-16T00:00:00'
      AND HAN.HANVORAR = '10'
      AND Kunde.KUNNR IN ('021496','021497','021498','021499','021500','021501')""")
    table_098 = cursor.fetchall()

    # 099 Vitfrisch
    cursor.execute("""SELECT DISTINCT HAN.HANVORNR, HAN.HANADRNR, Kunde.KUNKUBEZ, HAN.HANKENNG, CONVERT(DATE, HAN.HANVORDT) AS Datum
      FROM [db_GRS_099].[dbo].[tbl_HAN] AS HAN
      JOIN db_GRS_099.dbo.tbl_KUN AS Kunde ON Kunde.KUNNR = HAN.HANADRNR
      WHERE HANVORDT = '2021-07-16T00:00:00'
      AND HAN.HANVORAR = '10'
      AND Kunde.KUNNR IN ('021496','021497','021498','021499','021500','021501')""")
    table_099 = cursor.fetchall()
    cursor.close()

    table_combined = table_000 + table_098 + table_099

    for item in table_combined:
      temp_dict = {
        'lieferschein'  : item[0],
        'kundennummer'  : item[1],
        'kundenname'    : item[2].strip(),
        'bestellnummer' : item[3].strip(),
        'liefertag'     : item[4]
      }
      final_data.append(temp_dict)

    os.environ['NO_PROXY'] = 'localhost'
    request = requests.post('http://localhost/qweqwe', json = json.dumps(final_data))

    final_data.clear()

    print(f'{time.ctime()}     Status: {request.status_code}. {request.text}')

  except Exception as e:
    print(e.message)

  time.sleep(3600)