<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SqlServerNativeAuditController
{
    /**
     * Cria tabela de auditoria + trigger
     */
    public function createAuditTrigger()
    {
        DB::unprepared("
        IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'AUDIT_DISPOSITIVOS')
        BEGIN
            CREATE TABLE AUDIT_DISPOSITIVOS (
                id INT IDENTITY(1,1) PRIMARY KEY,
                device_id VARCHAR(50) NULL,
                operation VARCHAR(10),
                old_eqp_id VARCHAR(50) NULL,
                new_eqp_id VARCHAR(50) NULL,
                user_name VARCHAR(100),
                host_name VARCHAR(100),
                ip_address VARCHAR(50) NULL,
                event_time DATETIME DEFAULT GETDATE()
            );
        END
    ");

        DB::unprepared("
        CREATE OR ALTER TRIGGER trg_audit_dispositivos
        ON DISPOSITIVOS
        AFTER INSERT, UPDATE, DELETE
        AS
        BEGIN
            SET NOCOUNT ON;

            DECLARE @ip VARCHAR(50);
            SET @ip = CONVERT(VARCHAR(50), CONNECTIONPROPERTY('client_net_address'));

            INSERT INTO AUDIT_DISPOSITIVOS (
                device_id,
                operation,
                old_eqp_id,
                new_eqp_id,
                user_name,
                host_name,
                ip_address,
                event_time
            )
            SELECT
                COALESCE(i.EQP_ID, d.EQP_ID),
                CASE
                    WHEN i.EQP_ID IS NOT NULL AND d.EQP_ID IS NULL THEN 'INSERT'
                    WHEN i.EQP_ID IS NOT NULL AND d.EQP_ID IS NOT NULL THEN 'UPDATE'
                    WHEN i.EQP_ID IS NULL AND d.EQP_ID IS NOT NULL THEN 'DELETE'
                END,
                d.EQP_ID,
                i.EQP_ID,
                SYSTEM_USER,
                HOST_NAME(),
                @ip,
                GETDATE()
            FROM inserted i
            FULL JOIN deleted d ON i.EQP_ID = d.EQP_ID;
        END
    ");

        return response()->json([
            'message' => 'Trigger de auditoria avançada criada'
        ]);
    }


    /**
     * Teste seguro: dispara UPDATE sem alterar dados reais
     * (serve para descobrir quem está sobrescrevendo)
     */
    public function testNoChangeUpdate()
    {
        $deviceId = 'BAA519'; // altere se quiser testar outro dispositivo

        DB::update("
            UPDATE DISPOSITIVOS
            SET EQP_ID = EQP_ID
            WHERE EQP_ID = ?
        ", [$deviceId]);

        return response()->json([
            'message' => 'Update neutro executado (nenhum dado real foi alterado)',
            'device_id' => $deviceId,
            'time' => now()
        ]);
    }

    /**
     * Lê últimos logs de auditoria
     */
    public function readAuditLog()
    {
        $logs = DB::select("
        SELECT TOP 100
            device_id,
            operation,
            old_eqp_id,
            new_eqp_id,
            user_name,
            host_name,
            ip_address,
            event_time
        FROM AUDIT_DISPOSITIVOS
        ORDER BY event_time DESC
    ");

        return response()->json([
            'count' => count($logs),
            'logs' => $logs
        ]);
    }


    /**
     * Remove trigger + tabela de auditoria
     */
    public function dropAuditTrigger()
    {
        DB::unprepared("
            IF EXISTS (SELECT * FROM sys.triggers WHERE name = 'trg_audit_dispositivos')
                DROP TRIGGER trg_audit_dispositivos;
        ");

        DB::unprepared("
            IF EXISTS (SELECT * FROM sys.tables WHERE name = 'AUDIT_DISPOSITIVOS')
                DROP TABLE AUDIT_DISPOSITIVOS;
        ");

        return response()->json([
            'message' => 'Auditoria removida (trigger + tabela)'
        ]);
    }
}
