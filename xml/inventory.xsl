<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/">
    <html>
      <head>
        <title>RedTrace — Blood Inventory</title>
        <style>
          body { font-family: sans-serif; background: #fdf5f5; margin: 0; padding: 2rem; }
          h1 { color: #c0392b; }
          table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
          th { background: #c0392b; color: white; padding: 0.75rem; text-align: left; font-size: 0.85rem; }
          td { padding: 0.75rem; border-bottom: 1px solid #eee; font-size: 0.9rem; }
          tr:last-child td { border-bottom: none; }
          .available { color: #27ae60; font-weight: bold; }
          .reserved { color: #2980b9; font-weight: bold; }
          .expired { color: #c0392b; font-weight: bold; }
        </style>
      </head>
      <body>
        <h1>Mapua RedTrace — Blood Inventory</h1>
        <p>Total Units: <strong><xsl:value-of select="count(inventory/unit)"/></strong></p>
        <table>
          <thead>
            <tr>
              <th>Unit ID</th>
              <th>Blood Type</th>
              <th>Quantity (mL)</th>
              <th>Storage Location</th>
              <th>Expiration Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <xsl:apply-templates select="inventory/unit"/>
          </tbody>
        </table>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="unit">
    <tr>
      <td><xsl:value-of select="@id"/></td>
      <td><strong><xsl:value-of select="blood_type"/></strong></td>
      <td><xsl:value-of select="quantity_ml"/></td>
      <td><xsl:value-of select="storage_location"/></td>
      <td><xsl:value-of select="expiration_date"/></td>
      <td>
        <xsl:attribute name="class">
          <xsl:choose>
            <xsl:when test="inventory_status = 'Available'">available</xsl:when>
            <xsl:when test="inventory_status = 'Reserved'">reserved</xsl:when>
            <xsl:otherwise>expired</xsl:otherwise>
          </xsl:choose>
        </xsl:attribute>
        <xsl:value-of select="inventory_status"/>
      </td>
    </tr>
  </xsl:template>

</xsl:stylesheet>
